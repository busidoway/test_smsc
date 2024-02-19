<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sendsms;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\SendsmsCustomerJoin;
use App\Models\SendsmsStat;

class SendsmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        foreach ($data_customers as $item){
            $date_birth = $item->date;
            $phone = $item->phone;
            $time_param = $date_birth . $data->time;
            $time = date('d.m.Y H:i', strtotime($time_param . '-7 days'));

            $message = str_replace('{name}', $item->name, $data->message);

            $send = send_sms($phone, $message, 0, $time);

            if($send){
                $sendsms_stat = SendsmsStat::create([
                    'sendsms_id' => $sendsms->id,
                    'sms_id' => $send['id']
                ]);
            }

        }

        // $phones = implode(",", $data_phones);
        // if(isset($sendsms->message)) $message = $sendsms->message;
        //
        // if(isset($sendsms->time)){
        //     $time = $sendsms->time;
        //     $time_params = date('d.m.Y '.$time, strtotime('+7 days'));
        // }



        return ['sendsms' => $sendsms];

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
