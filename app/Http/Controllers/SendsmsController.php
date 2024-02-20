<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sendsms;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\SendsmsCustomerJoin;
use App\Models\SendsmsStat;
use Illuminate\Support\Arr;

class SendsmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        date_default_timezone_set("Europe/Moscow");

        $sendsms = Sendsms::all();

        $sendsms_arr = [];
        $data_req = json_decode($request->data);

        // return ['data' => $data_req];

        if(isset($data_req->date_from) && !empty($data_req->date_from)) $date_from = strtotime($data_req->date_from);
        if(isset($data_req->date_to) && !empty($data_req->date_to)) $date_to = strtotime($data_req->date);

        foreach($sendsms as $item){
            $sendsms_stat = SendsmsStat::select('sms_id')->where('sendsms_id', $item->id)->get();
            $count_delivered = 0;
            $count_wait = 0;
            $status_arr = [];
            $date_send = '';
            foreach($sendsms_stat as $stat){
                // $sendsms_customer_join = SendsmsCustomerJoin::select('')
                // $stat_phone = Customer::select('phone')->where('id', $stat->sms_id)->first();

                $stat_phone = DB::table('customers')
                    ->select('phone')
                    ->join('sendsms_customer_joins', 'sendsms_customer_joins.customer_id', '=', 'customers.id')
                    ->where('sendsms_customer_joins.sendsms_id', $item->id)
                    ->first();

                $status = get_status($stat->sms_id, $stat_phone->phone, 1);

                if($status[0] == 1 || $status[0] == -1){
                    // $date_send = date('d.m.y H:i:s', $status[3]);
                    if(isset($date_from) && $date_from > $status[3]){

                    }
                }

                if($status[0] == 1){
                    if(isset($date_from) && $status[3] >= $date_from) {
                        $count_delivered++;
                    }elseif(isset($date_to) && $status[3] <= $date_to){
                        $count_delivered++;
                    }elseif((isset($date_from) && isset($date_to)) && ($status[3] >= $date_from && $status[3] <= $date_to)){
                        $count_delivered++;
                    }
                    // else{
                    //     $count_delivered++;
                    // }
                }elseif($status[0] == -1){
                    $count_wait++;
                }

                // $status_arr[] = $status;
                // dd($status[2]);
                // $status1 = (string)$status[1];

            }

            $sendsms_arr[] = [
                'id' => $item->id,
                'name' => $item->name,
                'delivered' => $count_delivered,
                'wait' => $count_wait,
                // 'date_send' => $date_send,
                // 'status' => $status_arr
            ];
        }

        // $sendsms_stat = DB::table('sendsms_stats')->select('sms_id')->where('sendsms_id', 45)->get();
        // $new_arr = [];
        // $arr_phones = [];
        // foreach($sendsms_stat as $item){
        //     $new_arr[] = $item->sms_id;
        //     $arr_phones[] = Customer::select('phone')->where('id', $item->sms_id)->first();
        // }
        //
        // // $ddd = (Array)$sendsms_stat;
        // $ddd = array_values(json_decode(json_encode ( $sendsms_stat ) , true));
        // $sms_id_stat = implode(',', $new_arr);
        // $phones = implode(',', $arr_phones);

        // $status = get_status($sms_id_stat, $phones, 1);


        return ['sendsms' => $sendsms_arr];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d H:i:s');

        $data = json_decode($request->data);

        // return ['type' => $data->type];

        if(empty($data->name) && empty($data->message)){
            return ['error' => 'Поля "Название" и "Текст" обязательны'];
        }

        // return ['sendsms' => $data->customers];

        $sendsms = Sendsms::create([
            "name" => $data->name,
            "message" => $data->message,
            "time" => $data->time
        ]);

        $data_customers_id = [];

        if(!empty($data->customers)){
            foreach ($data->customers as $item){
                $sendsms_customer_join = SendsmsCustomerJoin::create([
                    "sendsms_id" => $sendsms->id,
                    "customer_id" => $item->id
                ]);

                $data_customers_id[] = $item->id;
            }
        }

        $data_customers = Customer::whereIn('id', $data_customers_id)->get();

        // $data_phones = [];
        // $data_date = [];

        $send_log = [];

        foreach ($data_customers as $item){
            $time = 0;
            $phone = $item->phone;
            $message = str_replace('{name}', $item->name, $data->message);

            if($data->type == 'reg'){
                $curr_year = date('Y');
                $date_birth = date('d.m.' . $curr_year , strtotime($item->date));
                $time_param = $date_birth . $data->time;

                if($data->count_days > 0) $time_param .= '-' . $data->count_days . ' days';

                $time = date('d.m.y H:i', strtotime($time_param));
            }

            $send = send_sms($phone, $message,0, $time);

            if(isset($send[0])){
                $sendsms_stat = SendsmsStat::create([
                    'sendsms_id' => $sendsms->id,
                    'sms_id' => $send[0]
                ]);
                $send_log[] = $send;
            }

        }

        // $phones = implode(",", $data_phones);
        // if(isset($sendsms->message)) $message = $sendsms->message;
        //
        // if(isset($sendsms->time)){
        //     $time = $sendsms->time;
        //     $time_params = date('d.m.Y '.$time, strtotime('+7 days'));
        // }



        return ['sendsms' => $sendsms, 'send_log' => $send_log];

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sendSms(Request $request)
    {
        $phone = '+79655594878';
        $mess = 'Hello!';

        $data = json_decode($request->check_customers);

        $customers = Customer::whereIn('id', $data)->get();

        // $send = send_sms($phone, $mess);

        return ['smsc' => $customers];
    }
}
