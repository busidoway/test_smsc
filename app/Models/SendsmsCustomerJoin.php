<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendsmsCustomerJoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'sendsms_id',
        'customer_id'
    ];
}
