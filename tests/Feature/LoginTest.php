<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_opened(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::create([
            'name' => 'Admin Testing',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('PasswordTest123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin-test@example.com',
            'password' => 'PasswordTest123!',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('login_attempts', [
            'user_id' => $user->id,
            'is_successful' => 1,
        ]);
    }

    public function test_login_fails_with_incorrect_password(): void
    {
        User::create([
            'name' => 'Admin Testing',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('PasswordTest123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin-test@example.com',
            'password' => 'PasswordSalah123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseHas('login_attempts', [
            'attempted_identifier' => 'admin-test@example.com',
            'is_successful' => 0,
        ]);
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::create([
            'name' => 'Admin Nonaktif',
            'email' => 'inactive-test@example.com',
            'password' => bcrypt('PasswordTest123!'),
            'role' => 'admin',
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive-test@example.com',
            'password' => 'PasswordTest123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
