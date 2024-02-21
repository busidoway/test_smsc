<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\SendsmsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('customers', [CustomersController::class, 'index']);

Route::post('customers_store', [CustomersController::class, 'store']);

Route::get('customers_edit/{id}', [CustomersController::class, 'edit']);

Route::post('customers_update/{id}', [CustomersController::class, 'update']);

Route::post('customers_delete/{id}', [CustomersController::class, 'destroy']);

Route::post('sendsms', [SendsmsController::class, 'index']);

// Route::post('send_sms', [SendsmsController::class, 'sendSms']);

Route::post('sendsms_store', [SendsmsController::class, 'store']);

Route::get('sendsms_edit/{id}', [SendsmsController::class, 'edit']);

Route::post('sendsms_update/{id}', [SendsmsController::class, 'update']);

Route::post('sendsms_delete/{id}', [SendsmsController::class, 'destroy']);
