@extends('layouts.admin')

@section('title', 'Akun Saya')

@section('content')
    <x-admin.page-header title="Akun Saya" subtitle="Ubah profil dan password login Anda." icon="fa-user-shield" />

    <div class="grid lg:grid-cols-2 gap-6 items-start">
        <!-- Profil -->
        <form action="{{ route('admin.account.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-100">
            @csrf
            @method('PUT')
            <div class="px-6 pt-6">
                <h2 class="font-extrabold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-id-badge text-tosca-600"></i> Profil</h2>
            </div>
            <div class="p-6 space-y-5">
                @php($profileErrors = $errors->getBag('profile'))
                @foreach (['name' => ['Nama Lengkap', 'text'], 'username' => ['Username', 'text'], 'email' => ['Email (opsional)', 'email']] as $field => [$label, $type])
                    <div>
                        <label for="p_{{ $field }}" class="block text-sm font-semibold text-slate-700 mb-1.5">{{ $label }}</label>
                        <input id="p_{{ $field }}" name="{{ $field }}" type="{{ $type }}" value="{{ old($field, $user->$field) }}" @required($field !== 'email')
                            class="w-full px-4 py-2.5 rounded-xl border text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none {{ $profileErrors->has($field) ? 'border-rose-400' : 'border-slate-200' }}">
                        @if ($profileErrors->has($field))
                            <p class="text-xs font-semibold text-rose-600 mt-1.5">{{ $profileErrors->first($field) }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 rounded-b-2xl text-right">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-tosca-700 hover:bg-tosca-800 text-white shadow-md transition hover:shadow-lg hover:-translate-y-0.5"><i class="fa-solid fa-floppy-disk text-gold-400 mr-1"></i> Simpan Profil</button>
            </div>
        </form>

        <!-- Password -->
        <form id="password" action="{{ route('admin.account.password') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-100 scroll-mt-24">
            @csrf
            @method('PUT')
            <div class="px-6 pt-6">
                <h2 class="font-extrabold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-key text-gold-500"></i> Ganti Password</h2>
                <p class="text-xs text-slate-500 mt-1">Minimal 8 karakter, berisi huruf dan angka. Perangkat lain yang sedang login akan otomatis keluar.</p>
            </div>
            <div class="p-6 space-y-5">
                @php($passwordErrors = $errors->getBag('password'))
                @foreach (['current_password' => ['Password Saat Ini', 'current-password'], 'password' => ['Password Baru', 'new-password'], 'password_confirmation' => ['Ulangi Password Baru', 'new-password']] as $field => [$label, $auto])
                    <div>
                        <label for="pw_{{ $field }}" class="block text-sm font-semibold text-slate-700 mb-1.5">{{ $label }}</label>
                        <input id="pw_{{ $field }}" name="{{ $field }}" type="password" required autocomplete="{{ $auto }}"
                            class="w-full px-4 py-2.5 rounded-xl border text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none {{ $passwordErrors->has($field) ? 'border-rose-400' : 'border-slate-200' }}">
                        @if ($passwordErrors->has($field))
                            <p class="text-xs font-semibold text-rose-600 mt-1.5">{{ $passwordErrors->first($field) }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 rounded-b-2xl text-right">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-gold-500 hover:bg-gold-600 text-slate-900 shadow-md transition hover:shadow-lg hover:-translate-y-0.5"><i class="fa-solid fa-key mr-1"></i> Ganti Password</button>
            </div>
        </form>
    </div>
@endsection
