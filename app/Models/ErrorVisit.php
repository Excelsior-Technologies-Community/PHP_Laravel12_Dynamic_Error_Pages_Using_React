<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorVisit extends Model
{
    protected $fillable = [

        'status_code',

        'url',

        'ip_address',

        'user_agent',

        'visited_at',

    ];

    protected $casts = [

        'visited_at' => 'datetime',

    ];
}