<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'reason',
        'threat_category',
        'blocked_by',
        'blocked_at',
    ];

    protected $casts = [
        'blocked_at' => 'datetime',
    ];
}
