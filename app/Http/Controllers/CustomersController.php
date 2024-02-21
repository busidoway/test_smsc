<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers_not_in = [];

        $data = json_decode($request->data);

        if(!empty($data)){
            foreach($data as $item){
                $customers_not_in[] = $item->id;
            }
        }

        $customers = Customer::select(DB::raw("id, name, phone, DATE_FORMAT(date, '%d.%m.%Y') as date"))->whereNotIn('id', $customers_not_in)->get();

        return ['customers' => $customers];
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
        $data = json_decode($request->data);

        if($data){
            $customer = Customer::create([
                "name" => $data->name,
                "phone" => $data->phone,
                "date" => date('Y-m-d', strtotime($data->date))
            ]);

            return ['customer' => $customer, 'status' => 'success'];
        }

        return ['status'=> 'undefined'];

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
        $customer = Customer::find($id);

        return ['customer' => $customer];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::find($id);

        $data = json_decode($request->data);

        $customer->name = $data->name;
        $customer->phone = $data->phone;
        $customer->date = $data->date;

        if($customer->isDirty()){
            $customer->save();
        }

        return ['customer' => $customer, 'status' => 'success'];

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::find($id);
        if($customer) {
            $customer->delete();
            return ["status" => "success"];
        }else{
            return ["status" => "error"];
        }
    }
}
