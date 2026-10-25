@php
    // Menu admin mengikuti urutan menu navbar website (lihat App\Support\AdminMenu).
    // Admin dengan akses terbatas hanya melihat menu yang dicentang di Pengguna Admin.
    $user = auth()->user();
    $settingsGroup = request()->routeIs('admin.settings.*') ? request()->route('group') : null;

    $menu = [['Dashboard', null, [
        ['Dashboard', 'fa-gauge-high', route('admin.dashboard'), request()->routeIs('admin.dashboard')],
    ]]];

    foreach (\App\Support\AdminMenu::groups() as $group) {
        $items = [];
        foreach ($group['items'] as $item) {
            if (! $user->canAccess($item['key'])) {
                continue;
            }
            $active = ($settingsGroup !== null && ($item['settings'] ?? null) === $settingsGroup)
                || (! empty($item['match']) && request()->routeIs(...$item['match']));
            $items[] = [$item['label'], $item['icon'], \App\Support\AdminMenu::url($item), $active];
        }
        if ($items) {
            $menu[$group['label']] = [$group['label'], $group['icon'], $items];
        }
    }

    // Menu akun (selalu ada) & manajemen pengguna (khusus Super Admin)
    $accountItems = array_values(array_filter([
        $user->isSuperAdmin() ? ['Pengguna Admin', 'fa-users-gear', route('admin.pengguna.index'), request()->routeIs('admin.pengguna.*')] : null,
        ['Akun Saya', 'fa-user-shield', route('admin.account.edit'), request()->routeIs('admin.account.*')],
    ]));
    $menu['Pengaturan'] = ['Pengaturan', 'fa-gear', array_merge($menu['Pengaturan'][2] ?? [], $accountItems)];
@endphp
<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-tosca-950 text-slate-300 flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <!-- Logo -->
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-6 h-20 shrink-0 border-b border-tosca-900">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-11 h-11 object-contain shrink-0">
        <div class="leading-tight">
            <span class="block text-sm font-extrabold text-white tracking-tight">MTs. DARUL ULUM WARU</span>
            <span class="block text-[10px] font-bold text-gold-400 tracking-widest uppercase">Panel Admin</span>
        </div>
    </a>

    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5 admin-scroll">
        @foreach ($menu as [$group, $groupIcon, $items])
            <div>
                @if ($groupIcon)
                    <p class="px-3 mb-1.5 text-[10.5px] font-bold uppercase tracking-widest text-tosca-300/60">{{ $group }}</p>
                @endif
                <div class="space-y-0.5">
                    @foreach ($items as [$label, $icon, $url, $active])
                        <a href="{{ $url }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ $active ? 'bg-tosca-700 text-white shadow-lg shadow-tosca-950/50' : 'hover:bg-tosca-900 hover:text-gold-400' }}">
                            <i class="fa-solid {{ $icon }} w-5 text-center {{ $active ? 'text-gold-400' : 'text-tosca-400' }}"></i>
                            <span>{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="p-4 border-t border-tosca-900 shrink-0">
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl bg-gold-500 hover:bg-gold-600 text-slate-900 text-sm font-bold transition shadow-md hover:shadow-lg hover:-translate-y-0.5">
            <i class="fa-solid fa-globe"></i> Lihat Website
        </a>
    </div>
</aside>
<div id="admin-sidebar-backdrop" onclick="toggleSidebar(false)" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden lg:hidden"></div>
