<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SecurityEventController extends Controller
{
    public function show(SecurityEvent $securityEvent): View
    {
        $securityEvent->load('loginAttempt');

        return view('admin.security-events.show', [
            'event' => $securityEvent,
        ]);
    }

    public function resolve(SecurityEvent $securityEvent): RedirectResponse
    {
        if ($securityEvent->status === 'resolved') {
            return redirect()
                ->route('admin.security-events.show', $securityEvent)
                ->with('info', 'Event ini sudah diselesaikan.');
        }

        $securityEvent->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return redirect()
            ->route('admin.security-events.show', $securityEvent)
            ->with('success', 'Event keamanan berhasil diselesaikan.');
    }
}
