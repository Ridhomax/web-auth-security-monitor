<?php

namespace App\Services;

use App\Models\DetectionRule;
use App\Models\LoginAttempt;
use App\Models\SecurityEvent;

class BruteForceDetectionService
{
    /**
     * Periksa apakah percobaan login gagal memenuhi aturan deteksi.
     */
    public function detect(LoginAttempt $attempt): void
    {
        // Login berhasil tidak dihitung sebagai kegagalan.
        if ($attempt->is_successful) {
            return;
        }

        // Pastikan waktu percobaan tersedia.
        if (!$attempt->attempted_at) {
            return;
        }

        $attemptTime = $attempt->attempted_at;

        $rules = DetectionRule::query()
            ->where('is_enabled', true)
            ->get();

        foreach ($rules as $rule) {
            $windowStart = $attemptTime->copy()
                ->subSeconds($rule->window_seconds);

            $query = LoginAttempt::query()
                ->where('is_successful', false)
                ->whereBetween('attempted_at', [
                    $windowStart,
                    $attemptTime,
                ]);

            if ($rule->detection_scope === 'account') {
                $query->where(
                    'attempted_identifier',
                    $attempt->attempted_identifier
                );

                $eventType = 'BRUTE_FORCE_ACCOUNT';
                $description = 'Percobaan login gagal berulang pada akun yang sama.';
            } elseif ($rule->detection_scope === 'ip') {
                $query->where('ip_address', $attempt->ip_address);

                $eventType = 'BRUTE_FORCE_IP';
                $description = 'Percobaan login gagal berulang dari alamat IP yang sama.';
            } else {
                continue;
            }

            $failureCount = $query->count();

            if ($failureCount < $rule->threshold_count) {
                continue;
            }

            // Hindari membuat event terbuka yang sama untuk
            // akun atau IP dalam jendela waktu terkait.
            $existingEvent = SecurityEvent::query()
                ->where('event_type', $eventType)
                ->where('status', 'open')
                ->where('detected_at', '>=', $windowStart)
                ->where('detected_at', '<=', $attemptTime)
                ->whereHas('loginAttempt', function ($query) use (
                    $rule,
                    $attempt
                ) {
                    if ($rule->detection_scope === 'account') {
                        $query->where(
                            'attempted_identifier',
                            $attempt->attempted_identifier
                        );
                    } else {
                        $query->where(
                            'ip_address',
                            $attempt->ip_address
                        );
                    }
                })
                ->exists();

            if ($existingEvent) {
                continue;
            }

            SecurityEvent::create([
                'login_attempt_id' => $attempt->id,
                'event_type' => $eventType,
                'severity' => 'high',
                'description' => $description
                    . ' Total kegagalan dalam jendela waktu: '
                    . $failureCount
                    . '.',
                'status' => 'open',
                'detected_at' => $attemptTime,
            ]);
        }
    }
}
