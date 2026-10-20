<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Models\SecurityEvent;

class SecurityDashboardController extends Controller
{
    public function index()
    {
        $totalAttempts = LoginAttempt::count();

        $failedAttempts = LoginAttempt::where(
            'is_successful',
            false
        )->count();

        $successfulAttempts = LoginAttempt::where(
            'is_successful',
            true
        )->count();

        $openEvents = SecurityEvent::where(
            'status',
            'open'
        )->count();

        $recentEvents = SecurityEvent::with('loginAttempt')
            ->orderByDesc('detected_at')
            ->limit(10)
            ->get();

        return view('admin.security-dashboard', compact(
            'totalAttempts',
            'failedAttempts',
            'successfulAttempts',
            'openEvents',
            'recentEvents'
        ));
    }
}
