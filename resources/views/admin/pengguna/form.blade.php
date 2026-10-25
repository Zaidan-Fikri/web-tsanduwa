@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Pengguna' : 'Tambah Pengguna')

@php
    // Pengguna baru: default "pilih menu tertentu" (akses seperlunya). Pengguna lama tanpa batasan: "semua menu".
    $access = old('access', $item->exists ? ($item->permissions === null ? 'all' : 'custom') : 'custom');
    $checked = old('permissions', $item->permissions ?? []);
    $presets = [
        'Admin TU (Pembayaran)' => ['spp', 'spp-import', 'pembayaran'],
        'Humas (Berita & Galeri)' => ['berita', 'prestasi', 'galeri', 'agenda', 'slider'],
        'Panitia SPMB' => ['spmb', 'spmb-prosedur', 'spmb-persyaratan'],
    ];
@endphp

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

        <!-- Hak akses menu -->
        <div id="access-section" class="rounded-2xl border border-slate-200 p-5 bg-slate-50/50">
            <p class="font-extrabold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-key text-gold-500"></i> Hak Akses Menu</p>
            <p class="text-xs text-slate-500 mt-0.5">Tentukan menu panel admin yang boleh dibuka pengguna ini. Dashboard dan Akun Saya selalu tersedia.</p>

            <div class="grid sm:grid-cols-2 gap-3 mt-4">
                @foreach (['all' => ['Semua menu', 'Bisa membuka seluruh menu (kecuali Pengguna Admin).', 'fa-unlock'], 'custom' => ['Pilih menu tertentu', 'Hanya menu yang dicentang di bawah.', 'fa-list-check']] as $value => [$label, $help, $icon])
                    <label class="flex items-start gap-3 p-4 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-tosca-300 has-[:checked]:border-tosca-500 has-[:checked]:bg-tosca-50 transition">
                        <input type="radio" name="access" value="{{ $value }}" @checked($access === $value) class="mt-1 text-tosca-700 focus:ring-tosca-600" data-access-radio>
                        <span>
                            <span class="block text-sm font-bold text-slate-800"><i class="fa-solid {{ $icon }} text-tosca-600 mr-1"></i>{{ $label }}</span>
                            <span class="block text-xs text-slate-500">{{ $help }}</span>
                        </span>
                    </label>
                @endforeach
            </div>

            <div id="permission-list" class="mt-5 {{ $access === 'custom' ? '' : 'hidden' }}">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="text-xs font-semibold text-slate-500">Cepat pilih:</span>
                    @foreach ($presets as $label => $keys)
                        <button type="button" data-preset="{{ implode(',', $keys) }}" class="px-3 py-1.5 rounded-full text-xs font-bold bg-white border border-slate-200 text-slate-600 hover:border-tosca-600 hover:text-tosca-700 transition">{{ $label }}</button>
                    @endforeach
                    <button type="button" data-preset="" class="px-3 py-1.5 rounded-full text-xs font-bold text-slate-500 hover:bg-slate-100 transition">Kosongkan</button>
                </div>

                @error('permissions')
                    <p class="mb-3 text-xs font-semibold text-rose-600"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                @enderror

                <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach (\App\Support\AdminMenu::groups() as $group)
                        <div class="bg-white rounded-xl border border-slate-200 p-4" data-perm-group>
                            <label class="flex items-center gap-2 pb-2 mb-2 border-b border-slate-100 cursor-pointer">
                                <input type="checkbox" class="rounded border-slate-300 text-tosca-700 focus:ring-tosca-600" data-group-toggle>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500"><i class="fa-solid {{ $group['icon'] }} text-tosca-600 mr-1"></i>{{ $group['label'] }}</span>
                            </label>
                            <div class="space-y-1.5">
                                @foreach ($group['items'] as $menuItem)
                                    <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer hover:text-tosca-700">
                                        <input type="checkbox" name="permissions[]" value="{{ $menuItem['key'] }}" @checked(in_array($menuItem['key'], $checked, true)) class="rounded border-slate-300 text-tosca-700 focus:ring-tosca-600" data-perm>
                                        <i class="fa-solid {{ $menuItem['icon'] }} w-4 text-center text-slate-400"></i> {{ $menuItem['label'] }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-slate-500 mt-3"><span id="perm-count">0</span> menu dipilih.</p>
            </div>
        </div>

        <p id="superadmin-note" class="text-xs text-slate-500 hidden"><i class="fa-solid fa-circle-info text-tosca-600 mr-1"></i>Super Admin selalu bisa membuka semua menu, termasuk mengelola Pengguna Admin.</p>
    </x-admin.form-card>
@endsection

@push('scripts')
    <script>
        (() => {
            const role = document.getElementById('role-select') || document.querySelector('select[name=role]');
            const section = document.getElementById('access-section');
            const note = document.getElementById('superadmin-note');
            const list = document.getElementById('permission-list');
            const perms = [...document.querySelectorAll('[data-perm]')];

            const sync = () => {
                const isAdmin = role.value === 'admin';
                section.classList.toggle('hidden', !isAdmin);
                note.classList.toggle('hidden', isAdmin);
                const custom = document.querySelector('[data-access-radio]:checked')?.value === 'custom';
                list.classList.toggle('hidden', !custom);
                document.querySelectorAll('[data-perm-group]').forEach(g => {
                    const boxes = [...g.querySelectorAll('[data-perm]')];
                    const toggle = g.querySelector('[data-group-toggle]');
                    const on = boxes.filter(b => b.checked).length;
                    toggle.checked = on === boxes.length;
                    toggle.indeterminate = on > 0 && on < boxes.length;
                });
                document.getElementById('perm-count').textContent = perms.filter(p => p.checked).length;
            };

            role.addEventListener('change', sync);
            document.querySelectorAll('[data-access-radio]').forEach(r => r.addEventListener('change', sync));
            perms.forEach(p => p.addEventListener('change', sync));
            document.querySelectorAll('[data-group-toggle]').forEach(t => t.addEventListener('change', () => {
                t.closest('[data-perm-group]').querySelectorAll('[data-perm]').forEach(b => b.checked = t.checked);
                sync();
            }));
            document.querySelectorAll('[data-preset]').forEach(btn => btn.addEventListener('click', () => {
                const keys = btn.dataset.preset ? btn.dataset.preset.split(',') : [];
                perms.forEach(p => p.checked = keys.includes(p.value));
                sync();
            }));
            sync();
        })();
    </script>
@endpush
