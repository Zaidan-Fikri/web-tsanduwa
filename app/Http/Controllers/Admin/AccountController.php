<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin.account', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validateWithBag('profile', [
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'min:4', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user)],
            'email' => ['nullable', 'email:rfc', 'max:150', Rule::unique('users')->ignore($user)],
        ]);

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function password(Request $request)
    {
        $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'different:current_password', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.current_password' => 'Password saat ini salah.',
            'password.different' => 'Password baru harus berbeda dari password lama.',
        ]);

        $user = $request->user();
        $user->update(['password' => $request->input('password')]);

        // Keluarkan sesi di perangkat lain yang masih memakai password lama.
        Auth::logoutOtherDevices($request->input('password'));
        $request->session()->regenerate();

        return back()->with('success', 'Password berhasil diganti.');
    }
}
