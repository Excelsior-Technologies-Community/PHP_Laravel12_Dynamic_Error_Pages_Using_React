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
        'threat_level',
        'threat_category',
        'device_type',
        'browser',
        'os',
        'country',
        'country_code',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'status_code' => 'integer',
    ];

    public function reports()
    {
        return $this->hasMany(ErrorReport::class);
    }
}
