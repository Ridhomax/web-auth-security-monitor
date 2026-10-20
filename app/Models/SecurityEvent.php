<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityEvent extends Model
{
    protected $table = 'security_events';

    public $timestamps = false;

    protected $fillable = [
        'login_attempt_id',
        'event_type',
        'severity',
        'description',
        'status',
        'detected_at',
        'resolved_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function loginAttempt(): BelongsTo
    {
        return $this->belongsTo(LoginAttempt::class, 'login_attempt_id');
    }
}
