@props(['brandName' => null])

<div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/60 lg:hidden" @click="sidebarOpen = false"></div>

<aside
    class="fixed inset-y-0 left-0 z-50 w-80 border-r border-white/10 bg-slate-950/95 px-5 py-6 text-slate-100 shadow-2xl shadow-slate-950/40 backdrop-blur-xl transition-transform duration-300 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex h-full flex-col">
        <div class="flex items-center justify-between gap-3 border-b border-white/10 pb-6">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-500 text-sm font-black text-white shadow-lg shadow-brand-500/30">DS</span>
                <span>
                    <span class="block text-sm font-semibold tracking-[0.24em] text-brand-200">DSCM EVENT</span>
                    <span class="block text-xs text-slate-400">{{ $brandName ?? config('app.name') }}</span>
                </span>
            </a>

            <button type="button" class="rounded-full border border-white/10 p-2 text-slate-300 transition hover:bg-white/10 lg:hidden" @click="sidebarOpen = false">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>
        </div>

        <nav class="mt-6 space-y-2">
            @php
                $user = auth()->user();
                $navigation = [];

                if ($user && $user->isPanitia()) {
                    $navigation = [
                        ['label' => 'Dashboard', 'href' => route('attendance.dashboard'), 'active' => request()->routeIs('attendance.dashboard')],
                        ['label' => 'Scan QR', 'href' => route('attendance.scan-qr'), 'active' => request()->routeIs('attendance.scan-qr*')],
                        ['label' => 'Check-in Manual', 'href' => route('attendance.manual-checkin'), 'active' => request()->routeIs('attendance.manual-checkin*')],
                        ['label' => 'Riwayat Saya', 'href' => route('attendance.history'), 'active' => request()->routeIs('attendance.history')],
                        ['label' => 'Profil', 'href' => route('attendance.profile'), 'active' => request()->routeIs('attendance.profile')],
                    ];
                } else {
                    $navigation = [
                        ['label' => 'Dashboard', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
                        ['label' => 'Peserta', 'href' => route('dashboard.participants'), 'active' => request()->routeIs('dashboard.participants')],
                        ['label' => 'Panitia', 'href' => route('admin.panitia.index'), 'active' => request()->routeIs('admin.panitia*')],
                        ['label' => 'Kehadiran', 'href' => route('admin.attendance.dashboard'), 'active' => request()->routeIs('admin.attendance*')],
                        ['label' => 'WhatsApp Setting', 'href' => route('dashboard.whatsapp-settings'), 'active' => request()->routeIs('dashboard.whatsapp-settings*')],
                    ];
                }
            @endphp

            @foreach ($navigation as $item)
                <a
                    href="{{ $item['href'] }}"
                    class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ $item['active'] ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
                >
                    <span class="h-2.5 w-2.5 rounded-full {{ $item['active'] ? 'bg-brand-400' : 'bg-slate-500 group-hover:bg-brand-300' }}"></span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="mt-auto rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-200">Foundation ready</p>
            <p class="mt-3 text-sm leading-6 text-slate-300">This shell is built for future events, seasonal campaigns, and church-friendly programs.</p>

            <div class="mt-5 space-y-3">
                <x-ui.button :href="route('landing')" variant="secondary" class="w-full">
                    View public site
                </x-ui.button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-ui.button type="submit" variant="danger" class="w-full">
                        Logout
                    </x-ui.button>
                </form>
            </div>
        </div>
    </div>
</aside>
