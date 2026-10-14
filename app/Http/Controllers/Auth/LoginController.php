<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /** Maksimal percobaan gagal sebelum dikunci sementara. */
    private const MAX_ATTEMPTS = 5;

    /** Lama penguncian (detik). */
    private const DECAY_SECONDS = 300;

    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $minutes = (int) ceil(RateLimiter::availableIn($key) / 60);

            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan login. Coba lagi dalam {$minutes} menit.",
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($key, self::DECAY_SECONDS);
            Log::warning('Login admin gagal', ['username' => $credentials['username'], 'ip' => $request->ip()]);

            // Pesan dibuat umum agar tidak membocorkan username mana yang terdaftar.
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        $request->user()->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'Anda telah keluar.');
    }

    private function throttleKey(Request $request): string
    {
        return 'admin-login|'.Str::transliterate(Str::lower($request->input('username'))).'|'.$request->ip();
    }
}
