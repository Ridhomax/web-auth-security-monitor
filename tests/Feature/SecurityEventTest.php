<?php

namespace Tests\Feature;

use App\Models\LoginAttempt;
use App\Models\SecurityEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityEventTest extends TestCase
{
    use RefreshDatabase;

    // =====================================================
    // TEST 1: ADMIN DAPAT MENYELESAIKAN SECURITY EVENT
    // =====================================================

    public function test_admin_can_resolve_security_event(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('TestPassword123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $loginAttempt = LoginAttempt::create([
            'user_id' => $admin->id,
            'attempted_identifier' => $admin->email,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'SecurityEventTest',
            'is_successful' => false,
            'failure_reason' => 'Invalid password',
            'attempted_at' => now(),
        ]);

        $event = SecurityEvent::create([
            'login_attempt_id' => $loginAttempt->id,
            'event_type' => 'brute_force',
            'severity' => 'high',
            'description' => 'Test security event',
            'status' => 'open',
            'detected_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.security-events.resolve', $event));

        $response->assertRedirect(
            route('admin.security-events.show', $event)
        );

        $this->assertDatabaseHas('security_events', [
            'id' => $event->id,
            'status' => 'resolved',
        ]);

        $this->assertNotNull($event->fresh()->resolved_at);
    }

    // =====================================================
    // TEST 2: STUDENT TIDAK DAPAT MELIHAT SECURITY EVENT
    // =====================================================

    public function test_student_cannot_view_security_event(): void
    {
        $student = User::create([
            'name' => 'Student Test',
            'email' => 'student-security@example.com',
            'password' => bcrypt('TestPassword123!'),
            'role' => 'student',
            'is_active' => true,
        ]);

        $loginAttempt = LoginAttempt::create([
            'user_id' => $student->id,
            'attempted_identifier' => $student->email,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'SecurityEventTest',
            'is_successful' => false,
            'failure_reason' => 'Invalid password',
            'attempted_at' => now(),
        ]);

        $event = SecurityEvent::create([
            'login_attempt_id' => $loginAttempt->id,
            'event_type' => 'BRUTE_FORCE_IP',
            'severity' => 'high',
            'description' => 'Pengujian akses student.',
            'status' => 'open',
            'detected_at' => now(),
        ]);

        $response = $this->actingAs($student)
            ->get(route('admin.security-events.show', $event));

        $response->assertForbidden();
    }

    // =====================================================
    // TEST 3: STUDENT TIDAK DAPAT MENYELESAIKAN SECURITY EVENT
    // =====================================================

    public function test_student_cannot_resolve_security_event(): void
    {
        $student = User::create([
            'name' => 'Student Test',
            'email' => 'student-security@example.com',
            'password' => bcrypt('TestPassword123!'),
            'role' => 'student',
            'is_active' => true,
        ]);

        $loginAttempt = LoginAttempt::create([
            'user_id' => $student->id,
            'attempted_identifier' => $student->email,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'SecurityEventTest',
            'is_successful' => false,
            'failure_reason' => 'Invalid password',
            'attempted_at' => now(),
        ]);

        $event = SecurityEvent::create([
            'login_attempt_id' => $loginAttempt->id,
            'event_type' => 'BRUTE_FORCE_ACCOUNT',
            'severity' => 'high',
            'description' => 'Pengujian perubahan status.',
            'status' => 'open',
            'detected_at' => now(),
        ]);

        $response = $this->actingAs($student)
            ->patch(route('admin.security-events.resolve', $event));

        $response->assertForbidden();

        $this->assertDatabaseHas('security_events', [
            'id' => $event->id,
            'status' => 'open',
            'resolved_at' => null,
        ]);
    }
}