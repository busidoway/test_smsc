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

        if(!isset($data_req->date_from) && !empty($data_req->date_from)) $date_from = strtotime($data_req->date_from);
        if(isset($data_req->date_to) && !empty($data_req->date_to)) $date_to = strtotime($data_req->date_to);

        foreach($sendsms as $item){
            $sendsms_stat = SendsmsStat::select('sms_id')->where('sendsms_id', $item->id)->get();
            $count_delivered = 0;
            $count_wait = 0;
            $status_arr = [];
            $date_send = [];
            foreach($sendsms_stat as $stat){

                $stat_phone = DB::table('customers')
                    ->select('phone')
                    ->join('sendsms_customer_joins', 'sendsms_customer_joins.customer_id', '=', 'customers.id')
                    ->where('sendsms_customer_joins.sendsms_id', $item->id)
                    ->first();

                $status = get_status($stat->sms_id, $stat_phone->phone, 1);

                if($status[0] == 1){
                    if(isset($date_from) || isset($date_to)) {
                        if (isset($date_from) && $status[3] >= $date_from && !isset($date_to)) {
                            $count_delivered++;
                        } elseif (isset($date_to) && $status[3] <= $date_to && !isset($date_from)) {
                            $count_delivered++;
                        } elseif ((isset($date_from) && isset($date_to)) && ($status[3] >= $date_from && $status[3] <= $date_to)) {
                            $count_delivered++;
                        }
                    }
                    else{
                        $count_delivered++;
                    }
                }elseif($status[0] == -1){
                    if(isset($date_from) || isset($date_to)) {
                        if (isset($date_from) && $status[3] >= $date_from && !isset($date_to)) {
                            $count_wait++;
                        } elseif (isset($date_to) && $status[3] <= $date_to && !isset($date_from)) {
                            $count_wait++;
                        } elseif ((isset($date_from) && isset($date_to)) && ($status[3] >= $date_from && $status[3] <= $date_to)) {
                            $count_wait++;
                        }
                    }
                    else{
                        $count_wait++;
                    }
                }

                $status_arr[] = $status;

            }

            $sendsms_arr[] = [
                'id' => $item->id,
                'name' => $item->name,
                'delivered' => $count_delivered,
                'wait' => $count_wait,
                // 'date_send' => $date_send,
                'status' => $status_arr
            ];
        }

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

        $data = json_decode($request->data);

        if(empty($data->name) && empty($data->message)){
            return ['status' => 'error', 'info' => 'Поля "Название" и "Текст" обязательны'];
        }

        $sendsms = Sendsms::create([
            "name" => $data->name,
            "message" => $data->message,
            "time" => $data->time,
            "count_days" => $data->count_days
        ]);

        if(!empty($data->customers)){
            foreach ($data->customers as $item){
                $sendsms_customer_join = SendsmsCustomerJoin::create([
                    "sendsms_id" => $sendsms->id,
                    "customer_id" => $item->id
                ]);
            }
        }

        if($data->active_send === true){
            $send_sms = $this->sendSms($data, $sendsms->id);

            if($send_sms['status'] === 'error'){
                return ['status' => 'error'];
            }else{
                return ['sendsms' => $sendsms, 'send_log' => $send_sms['send_log'], 'status' => 'success'];
            }
        }else{
            return ['sendsms' => $sendsms, 'status' => 'success'];
        }

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
        $sendsms = Sendsms::find($id);

        $customers = DB::table('customers')
            ->select('*')
            ->join('sendsms_customer_joins as scj', 'scj.customer_id', '=', 'customers.id')
            ->where('scj.sendsms_id', $id)
            ->get();

        return ['sendsms' => $sendsms, 'customers' => $customers];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sendsms = Sendsms::find($id);

        $data = json_decode($request->data);

        $sendsms->name = $data->name;
        $sendsms->message = $data->message;
        $sendsms->time = $data->time;
        $sendsms->count_days = $data->count_days;

        if($sendsms->isDirty()){
            $sendsms->save();
        }

        if($data->active_send === true){
            $send_sms = $this->sendSms($data, $sendsms->id);

            if($send_sms['status'] === 'error'){
                return ['status' => 'error'];
            }else{
                return ['sendsms' => $sendsms, 'send_log' => $send_sms['send_log'], 'status' => 'success'];
            }
        }else{
            return ['sendsms' => $sendsms, 'status' => 'success'];
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sendsms = Sendsms::find($id);
        if($sendsms) {
            $sendsms->delete();
            return ["status" => "success"];
        }else{
            return ["status" => "error"];
        }
    }

    public function sendSms($data, $sendsms_id)
    {
        date_default_timezone_set("Europe/Moscow");

        $sendsms_customer_join = SendsmsCustomerJoin::where('sendsms_id', $sendsms_id)->get();
        $data_customers_id = [];

        foreach ($sendsms_customer_join as $item){
            $data_customers_id[] = $item->customer_id;
        }

        $data_customers = Customer::whereIn('id', $data_customers_id)->get();

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
                    'sendsms_id' => $sendsms_id,
                    'sms_id' => $send[0]
                ]);
                $send_log[] = $send;
            }

        }

        return ['status' => 'success', 'send_log' => $send_log];
    }
}
