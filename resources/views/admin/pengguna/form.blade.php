@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Pengguna' : 'Tambah Pengguna')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Pengguna' : 'Tambah Pengguna'" subtitle="Akun panel admin" icon="fa-users-gear" />

    <x-admin.form-card :action="$item->exists ? route('admin.pengguna.update', $item) : route('admin.pengguna.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.pengguna.index')">
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="name" label="Nama Lengkap" :value="$item->name" required />
            <x-admin.input name="username" label="Username" :value="$item->username" required autocomplete="off" help="Minimal 4 karakter: huruf, angka, - atau _." />
            <x-admin.input name="email" label="Email (opsional)" type="email" :value="$item->email" />
            <x-admin.select name="role" label="Peran" :options="\App\Models\User::ROLES" :value="$item->role" required />
            <x-admin.input name="password" label="{{ $item->exists ? 'Password Baru' : 'Password' }}" type="password" :required="! $item->exists" autocomplete="new-password" :help="$item->exists ? 'Kosongkan jika tidak ingin mengganti.' : 'Minimal 8 karakter, berisi huruf dan angka.'" />
            <x-admin.input name="password_confirmation" label="Ulangi Password" type="password" :required="! $item->exists" autocomplete="new-password" />
        </div>
    </x-admin.form-card>
@endsection
