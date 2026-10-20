<?php

namespace Tests\Feature;

use App\Models\DetectionRule;
use App\Models\LoginAttempt;
use App\Models\SecurityEvent;
use App\Services\BruteForceDetectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BruteForceDetectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_detection_service_can_be_resolved(): void
    {
        $service = app(BruteForceDetectionService::class);

        $this->assertInstanceOf(
            BruteForceDetectionService::class,
            $service
        );
    }

    public function test_successful_login_is_ignored(): void
    {
        $attempt = new LoginAttempt([
            'is_successful' => true,
            'attempted_at' => now(),
        ]);

        app(BruteForceDetectionService::class)->detect($attempt);

        $this->assertDatabaseCount('security_events', 0);
    }

    public function test_no_detection_rules_means_no_security_events(): void
    {
        $attempt = $this->createAttempt(
            'test@example.com',
            '192.0.2.10'
        );

        app(BruteForceDetectionService::class)->detect($attempt);

        $this->assertDatabaseCount('security_events', 0);
    }

    public function test_repeated_failures_for_same_account_are_detected(): void
    {
        $this->createRule('account');

        $service = app(BruteForceDetectionService::class);
        $attempts = $this->createFailureAttempts(
            'student@example.com',
            '192.0.2.10',
            3
        );

        foreach ($attempts as $attempt) {
            $service->detect($attempt);
        }

        $this->assertDatabaseHas('security_events', [
            'event_type' => 'BRUTE_FORCE_ACCOUNT',
            'status' => 'open',
        ]);
    }

    public function test_repeated_failures_from_same_ip_are_detected(): void
    {
        $this->createRule('ip');

        $service = app(BruteForceDetectionService::class);
        $attempts = $this->createFailureAttempts(
            'student1@example.com',
            '192.0.2.20',
            3
        );

        foreach ($attempts as $attempt) {
            $service->detect($attempt);
        }

        $this->assertDatabaseHas('security_events', [
            'event_type' => 'BRUTE_FORCE_IP',
            'status' => 'open',
        ]);
    }

    public function test_duplicate_open_events_are_not_created(): void
    {
        $this->createRule('account');

        $service = app(BruteForceDetectionService::class);
        $attempts = $this->createFailureAttempts(
            'student@example.com',
            '192.0.2.30',
            4
        );

        foreach ($attempts as $attempt) {
            $service->detect($attempt);
        }

        $this->assertSame(
            1,
            SecurityEvent::query()
                ->where('event_type', 'BRUTE_FORCE_ACCOUNT')
                ->where('status', 'open')
                ->count()
        );
    }

    private function createRule(string $scope): DetectionRule
    {
        return DetectionRule::create([
            'rule_name' => 'Test brute force ' . $scope,
            'detection_scope' => $scope,
            'threshold_count' => 3,
            'window_seconds' => 300,
            'is_enabled' => true,
        ]);
    }

    private function createAttempt(
        string $identifier,
        string $ip,
        int $secondsAgo = 0
    ): LoginAttempt {
        $attempt = new LoginAttempt([
            'attempted_identifier' => $identifier,
            'ip_address' => $ip,
            'is_successful' => false,
            'attempted_at' => now()->subSeconds($secondsAgo),
        ]);

        $attempt->save();

        return $attempt;
    }

    private function createFailureAttempts(
        string $identifier,
        string $ip,
        int $count
    ): array {
        $attempts = [];

        for ($i = $count - 1; $i >= 0; $i--) {
            $attempts[] = $this->createAttempt(
                $identifier,
                $ip,
                $i * 10
            );
        }

        return $attempts;
    }
}
