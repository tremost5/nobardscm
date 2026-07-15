<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Riwayat Kehadiran']]" />
    </x-slot>

    <div class="space-y-6">
        <div>
            <h1 class="font-display text-2xl font-semibold text-slate-950">Riwayat Kehadiran</h1>
            <p class="mt-1 text-sm text-slate-500">Lihat log semua check-in peserta</p>
        </div>

        <x-ui.card>
            <div class="space-y-6 border-b border-slate-200 pb-6">
                <div>
                    <h3 class="font-semibold text-slate-900">Filter</h3>
                </div>

                <form method="GET" action="{{ route('admin.attendance.log') }}" class="grid gap-4 sm:grid-cols-6">
                    <div>
                        <label for="date" class="block text-sm font-semibold text-slate-900">Tanggal</label>
                        <input type="date" id="date" name="date" value="{{ request('date') }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    </div>

                    <div>
                        <label for="panitia" class="block text-sm font-semibold text-slate-900">Panitia</label>
                        <select id="panitia" name="panitia" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <option value="">Semua Panitia</option>
                            @foreach ($panitiaList as $p)
                                <option value="{{ $p->id }}" @selected(request('panitia') == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="method" class="block text-sm font-semibold text-slate-900">Metode</label>
                        <select id="method" name="method" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <option value="">Semua Metode</option>
                            <option value="qr" @selected(request('method') === 'qr')>QR Scan</option>
                            <option value="manual" @selected(request('method') === 'manual')>Manual</option>
                        </select>
                    </div>

                    <div>
                        <label for="event_type" class="block text-sm font-semibold text-slate-900">Jenis</label>
                        <select id="event_type" name="event_type" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <option value="">Semua Jenis</option>
                            <option value="attendance.checkin" @selected(request('event_type') === 'attendance.checkin')>Check-in</option>
                        </select>
                    </div>

                    <div>
                        <label for="search" class="block text-sm font-semibold text-slate-900">Cari</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nama, no registrasi, atau keterangan" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-600 px-6 py-3 font-semibold text-white hover:bg-brand-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Waktu</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Peserta</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">No Registrasi</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Aksi</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Metode</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Panitia</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $log->logged_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-950">{{ $log->registration?->full_name ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $log->registration?->registration_number ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <x-ui.badge :tone="$log->action === 'checkin' ? 'emerald' : 'slate'">
                                        {{ $log->action ?? 'activity' }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4">
                                    <x-ui.badge :tone="$log->method === 'qr' ? 'brand' : 'amber'">
                                        {{ $log->method === 'qr' ? 'QR Scan' : ($log->method === 'manual' ? 'Manual' : ucfirst((string) $log->method)) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $log->user?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $log->description ?? $log->event_type ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-slate-500">Belum ada data kehadiran</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="mt-6 border-t border-slate-200 pt-6">
                    {{ $logs->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
