<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sendsms extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'message',
        'time',
        'status',
        'count_days'
    ];
}
