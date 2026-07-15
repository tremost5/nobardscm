<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-ui.stat-card label="Total peserta" :value="$totalParticipants" hint="Semua pendaftaran terkini." />
            <x-ui.stat-card label="Bawa snack" :value="$snackCount" hint="Peserta yang memilih membawa camilan." />
            <x-ui.stat-card label="WhatsApp" :value="$whatsappEnabled ? 'Aktif' : 'Nonaktif'" hint="Status pengiriman notifikasi." />
            <x-ui.stat-card label="Poster" :value="$posterPath !== '' ? 'Tersimpan' : 'Belum ada'" hint="Poster disimpan di storage." />
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <x-ui.card class="relative overflow-hidden">
                <div class="absolute -right-10 -top-10 h-36 w-36 rounded-full bg-amber-300/20 blur-3xl"></div>
                <div class="relative">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Dashboard</p>
                    @if ($event)
                        <h1 class="mt-2 font-display text-3xl font-semibold text-slate-950">{{ $event->title }}</h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-600">
                            Halaman ini merangkum performa registrasi saat ini. Struktur dasarnya tetap ringan sehingga mudah berkembang menjadi CMS event di fase berikutnya.
                        </p>

                        <div class="mt-6 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Tanggal</p>
                                <p class="mt-3 text-sm font-semibold text-slate-900">{{ $event->date_range_label }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Lokasi</p>
                                <p class="mt-3 text-sm font-semibold text-slate-900">{{ $event->location ?: 'To be announced' }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Status</p>
                                <p class="mt-3 text-sm font-semibold text-slate-900">{{ $event->status->label() }}</p>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                            <p class="text-lg font-semibold text-slate-900">Belum ada event aktif</p>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Recent</p>
                        <h2 class="mt-2 font-display text-2xl font-semibold text-slate-950">Peserta terbaru</h2>
                    </div>
                    <x-ui.badge tone="brand">{{ $latestParticipants->count() }} data</x-ui.badge>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($latestParticipants as $participant)
                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-950">{{ $participant->full_name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $participant->church_name }}</p>
                                </div>
                                <x-ui.badge :tone="$participant->bring_snack ? 'emerald' : 'slate'">
                                    {{ $participant->bring_snack ? 'Snack' : 'Tanpa snack' }}
                                </x-ui.badge>
                            </div>
                            <p class="mt-3 text-xs uppercase tracking-[0.3em] text-slate-400">{{ $participant->created_at?->format('d M Y H:i') }}</p>
                        </div>
                    @empty
                        <x-ui.empty-state
                            title="Belum ada peserta"
                            description="Pendaftaran akan muncul di sini setelah form publik digunakan."
                            action-label="Lihat Peserta"
                            :action-href="route('dashboard.participants')"
                        />
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
