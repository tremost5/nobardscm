<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('attendance.dashboard')], ['label' => 'Attendance Dashboard']]"></x-ui.breadcrumbs>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-[2rem] border border-white/10 bg-slate-950/90 p-8 text-white shadow-2xl shadow-slate-950/30">
            <p class="text-sm font-semibold uppercase tracking-[0.32em] text-brand-200">Panitia Area</p>
            <h1 class="mt-3 font-display text-3xl font-semibold">Selamat datang, {{ auth()->user()->name }}</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">Gunakan menu di samping untuk melakukan check-in peserta melalui QR atau pencarian manual.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-ui.card>
                <p class="text-sm font-semibold text-slate-500">Scan QR</p>
                <p class="mt-3 text-2xl font-semibold text-slate-950">Cepat</p>
                <p class="mt-2 text-sm text-slate-500">Scan tiket peserta langsung dari layar acara.</p>
            </x-ui.card>
            <x-ui.card>
                <p class="text-sm font-semibold text-slate-500">Check-in Manual</p>
                <p class="mt-3 text-2xl font-semibold text-slate-950">Real-time</p>
                <p class="mt-2 text-sm text-slate-500">Cari peserta berdasarkan nama, nomor registrasi, atau WhatsApp.</p>
            </x-ui.card>
            <x-ui.card>
                <p class="text-sm font-semibold text-slate-500">Riwayat Saya</p>
                <p class="mt-3 text-2xl font-semibold text-slate-950">Terpantau</p>
                <p class="mt-2 text-sm text-slate-500">Pantau aktivitas check-in yang telah Anda lakukan.</p>
            </x-ui.card>
            <x-ui.card>
                <p class="text-sm font-semibold text-slate-500">Profil</p>
                <p class="mt-3 text-2xl font-semibold text-slate-950">Aman</p>
                <p class="mt-2 text-sm text-slate-500">Simpan data akun Anda untuk akses yang konsisten.</p>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
