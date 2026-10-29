<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Models\SecurityEvent;
use Illuminate\Http\Request;

class SecurityDashboardController extends Controller
{
    public function index(Request $request)
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

        // Ambil filter dan validasi nilainya.
        $status = $request->query('status');
        $severity = $request->query('severity');
        $search = trim($request->query('search', ''));

        $allowedStatuses = ['open', 'resolved'];
        $allowedSeverities = ['low', 'medium', 'high'];

        if (! in_array($status, $allowedStatuses, true)) {
            $status = null;
        }

        if (! in_array($severity, $allowedSeverities, true)) {
            $severity = null;
        }

        // Bangun query daftar event berdasarkan filter.
        $eventsQuery = SecurityEvent::with('loginAttempt')
            ->orderByDesc('detected_at');

        if ($status !== null) {
            $eventsQuery->where('status', $status);
        }

        if ($severity !== null) {
            $eventsQuery->where('severity', $severity);
        }

        if ($search !== '') {
            $eventsQuery->where(function ($query) use ($search) {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhereHas('loginAttempt', function ($attemptQuery) use ($search) {
                        $attemptQuery->where('ip_address', 'like', "%{$search}%");
                    });
            });
        }

        $recentEvents = $eventsQuery
            ->limit(10)
            ->get();

        return view('admin.security-dashboard', compact(
            'totalAttempts',
            'failedAttempts',
            'successfulAttempts',
            'openEvents',
            'recentEvents',
            'severity',
            'status',
            'search'
        ));
    }
}
