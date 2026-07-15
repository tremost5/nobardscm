<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Kehadiran']]" />
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            <x-ui.stat-card label="Total Peserta" :value="$totalParticipants" hint="Semua pendaftaran" />
            <x-ui.stat-card label="Sudah Hadir" :value="$presentCount" hint="Peserta hadir" />
            <x-ui.stat-card label="Belum Hadir" :value="$absentCount" hint="Peserta tidak hadir" />
            <x-ui.stat-card label="QR Scan" :value="$qrCount" hint="Check-in via QR" />
            <x-ui.stat-card label="Manual" :value="$manualCount" hint="Check-in manual" />
            <x-ui.stat-card label="Progress" :value="$attendancePercentage . '%'" hint="Tingkat kehadiran" />
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <x-ui.card>
                <h2 class="font-display text-xl font-semibold text-slate-950">Ringkasan Kehadiran</h2>
                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <span class="font-semibold text-slate-600">Total Peserta</span>
                        <span class="text-xl font-bold text-slate-950">{{ $totalParticipants }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                        <span class="font-semibold text-emerald-700">Sudah Hadir</span>
                        <span class="text-xl font-bold text-emerald-900">{{ $presentCount }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-red-200 bg-red-50 p-4">
                        <span class="font-semibold text-red-700">Belum Hadir</span>
                        <span class="text-xl font-bold text-red-900">{{ $absentCount }}</span>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="font-display text-xl font-semibold text-slate-950">Metode Check-in</h2>
                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between rounded-2xl border border-blue-200 bg-blue-50 p-4">
                        <span class="font-semibold text-blue-700">QR Scan</span>
                        <span class="text-xl font-bold text-blue-900">{{ $qrCount }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-purple-200 bg-purple-50 p-4">
                        <span class="font-semibold text-purple-700">Manual Check-in</span>
                        <span class="text-xl font-bold text-purple-900">{{ $manualCount }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <span class="font-semibold text-slate-600">Panitia Aktif</span>
                        <span class="text-xl font-bold text-slate-950">{{ $activePanitia }}</span>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('admin.export.excel') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                Export Excel
            </a>
        </div>
    </div>
</x-app-layout>
