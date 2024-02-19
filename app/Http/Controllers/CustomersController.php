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

        // if(!empty($request->data)){
        //
        // }else{
        //
        // }

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
}
