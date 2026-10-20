<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetectionRule extends Model
{
    protected $table = 'detection_rules';

    protected $fillable = [
        'rule_name',
        'detection_scope',
        'threshold_count',
        'window_seconds',
        'is_enabled',
    ];

    protected $casts = [
        'threshold_count' => 'integer',
        'window_seconds' => 'integer',
        'is_enabled' => 'boolean',
    ];
}
