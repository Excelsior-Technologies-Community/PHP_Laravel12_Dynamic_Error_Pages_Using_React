<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'error_visit_id',
        'status_code',
        'url',
        'user_message',
        'user_email',
        'diagnostic_data',
        'status',
    ];

    protected $casts = [
        'diagnostic_data' => 'array',
        'status_code' => 'integer',
    ];

    public function errorVisit()
    {
        return $this->belongsTo(ErrorVisit::class);
    }
}
