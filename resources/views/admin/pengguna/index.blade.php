@extends('layouts.admin')

@section('title', 'Pengguna Admin')

@section('content')
    <x-admin.page-header title="Pengguna Admin" subtitle="Akun yang dapat masuk ke panel admin." icon="fa-users-gear">
        <x-admin.add-button :href="route('admin.pengguna.create')" label="Tambah Pengguna" />
    </x-admin.page-header>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table w-full">
                <thead><tr><th>Nama</th><th>Peran</th><th>Akses Menu</th><th>Login Terakhir</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="w-10 h-10 rounded-full bg-gradient-to-br from-tosca-500 to-tosca-800 text-white font-bold flex items-center justify-center">{{ strtoupper(mb_substr($item->name, 0, 1)) }}</span>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $item->name }} @if ($item->is(auth()->user()))<span class="text-[10px] font-bold text-tosca-700">(Anda)</span>@endif</p>
                                        <p class="text-xs text-slate-500">{{ '@'.$item->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $item->isSuperAdmin() ? 'bg-gold-500 text-slate-950' : 'bg-tosca-100 text-tosca-800' }}">{{ $item->roleLabel() }}</span></td>
                            <td class="text-xs text-slate-600 max-w-xs">
                                @if ($item->hasFullAccess())
                                    <span class="font-semibold text-tosca-700"><i class="fa-solid fa-unlock mr-1"></i>Semua menu</span>
                                @else
                                    <span class="font-semibold text-slate-700">{{ count($item->accessLabels()) }} menu:</span> {{ implode(', ', $item->accessLabels()) }}
                                @endif
                            </td>
                            <td class="text-xs text-slate-600">
                                @if ($item->last_login_at)
                                    {{ $item->last_login_at->translatedFormat('d M Y H:i') }}
                                    <span class="block text-slate-400">IP {{ $item->last_login_ip }}</span>
                                @else
                                    <span class="text-slate-400">Belum pernah</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->is(auth()->user()))
                                    <div class="text-right"><a href="{{ route('admin.account.edit') }}" class="text-xs font-bold text-tosca-700 hover:underline">Akun Saya &rarr;</a></div>
                                @else
                                    <x-admin.row-actions :edit="route('admin.pengguna.edit', $item)" :destroy="route('admin.pengguna.destroy', $item)" :name="'pengguna '.$item->username" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 p-4 rounded-xl bg-tosca-50 border border-tosca-100 text-sm text-tosca-900">
        <i class="fa-solid fa-circle-info text-tosca-600 mr-1"></i>
        <b>Super Admin</b> dapat mengelola pengguna lain. <b>Admin</b> dapat mengelola menu yang diizinkan (semua menu atau menu tertentu), kecuali Pengguna Admin.
    </div>
@endsection
