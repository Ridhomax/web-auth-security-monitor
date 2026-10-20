<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LoginAttempt;
use App\Services\BruteForceDetectionService;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(
        Request $request,
        BruteForceDetectionService $detectionService
    ) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        $isSuccessful = $user
            && $user->is_active
            && Hash::check($credentials['password'], $user->password);

        $attempt = LoginAttempt::create([
            'user_id' => $user?->id,
            'attempted_identifier' => $credentials['email'],
            'ip_address' => $request->ip() ?? '0.0.0.0',
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'is_successful' => (bool) $isSuccessful,
            'failure_reason' => $isSuccessful
                ? null
                : 'invalid_credentials_or_inactive_account',
            'attempted_at' => now(),
        ]);

        if (! $isSuccessful) {
            $detectionService->detect($attempt);

            return back()
                ->withErrors([
                    'email' => 'Email atau password tidak valid, atau akun tidak aktif.',
                ])
                ->onlyInput('email');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
