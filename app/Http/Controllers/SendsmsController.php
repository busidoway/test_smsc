<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sendsms;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

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
        //
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
