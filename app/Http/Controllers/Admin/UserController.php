<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.pengguna.index', ['items' => User::orderBy('name')->get()]);
    }

    public function create()
    {
        return view('admin.pengguna.form', ['item' => new User(['role' => 'admin'])]);
    }

    public function store(Request $request)
    {
        User::create($this->validated($request));

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.pengguna.form', ['item' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, $user);

        if ($user->is($request->user()) && $data['role'] !== 'superadmin') {
            return back()->withErrors(['role' => 'Anda tidak dapat menurunkan peran akun Anda sendiri.'])->withInput();
        }

        $user->update($data);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->isSuperAdmin() && User::where('role', 'superadmin')->count() <= 1) {
            return back()->with('error', 'Minimal harus ada satu Super Admin.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'min:4', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user)],
            'email' => ['nullable', 'email:rfc', 'max:150', Rule::unique('users')->ignore($user)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(8)->letters()->numbers()],
            'access' => ['required_if:role,admin', 'nullable', Rule::in(['all', 'custom'])],
            'permissions' => ['exclude_unless:access,custom', 'exclude_if:role,superadmin', 'required', 'array', 'min:1'],
            'permissions.*' => ['string', Rule::in(AdminMenu::keys())],
        ], [
            'permissions.required' => 'Pilih minimal satu menu yang boleh diakses.',
            'permissions.min' => 'Pilih minimal satu menu yang boleh diakses.',
        ], ['access' => 'hak akses']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        // Super Admin & "Semua menu" = null (akses penuh); selain itu daftar kode menu yang dicentang.
        $data['permissions'] = $data['role'] === 'admin' && ($data['access'] ?? 'all') === 'custom'
            ? array_values(array_intersect(AdminMenu::keys(), $data['permissions']))
            : null;
        unset($data['access']);

        return $data;
    }
}
