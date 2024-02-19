<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendsmsStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sendsms_id',
        'sms_id'
    ];
}
