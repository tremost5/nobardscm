<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <x-ui.alert type="success">
                {{ session('status') }}
            </x-ui.alert>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <x-ui.stat-card label="Total peserta" :value="$totalParticipants" hint="Semua pendaftaran yang tersimpan." />
            <x-ui.stat-card label="Bawa snack" :value="$snackCount" hint="Peserta yang memilih membawa camilan." />
            <x-ui.stat-card label="Pencarian aktif" :value="$search !== '' ? 'Ya' : 'Tidak'" hint="Filter berdasarkan nama, gereja, atau WhatsApp." />
        </div>

        <x-ui.card>
            <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Peserta</p>
                    <h2 class="mt-2 font-display text-2xl font-semibold text-slate-950">Daftar registrasi</h2>
                    <p class="mt-2 text-sm text-slate-500">Cari peserta berdasarkan nama, gereja, atau nomor WhatsApp.</p>
                </div>

                <form method="GET" action="{{ route('dashboard.participants') }}" class="w-full lg:max-w-md">
                    <label class="sr-only" for="search">Search</label>
                    <div class="flex gap-3">
                        <input
                            id="search"
                            name="search"
                            type="search"
                            value="{{ $search }}"
                            placeholder="Cari peserta..."
                            class="block w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-brand-400 focus:ring-4 focus:ring-brand-500/10"
                        >
                        <x-ui.button type="submit">Search</x-ui.button>
                    </div>
                </form>
            </div>

            <x-ui.table class="mt-6">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Registrasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Gereja</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">WhatsApp</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Bawa Snack</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($participants as $participant)
                        <tr>
                            <td class="px-6 py-4 font-semibold text-slate-950">{{ $participant->registration_number }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-950">{{ $participant->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $participant->church_name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $participant->whatsapp_number }}</td>
                            <td class="px-6 py-4">
                                <x-ui.badge :tone="$participant->bring_snack ? 'emerald' : 'slate'">
                                    {{ $participant->bring_snack ? 'Ya' : 'Tidak' }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $participant->created_at?->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10">
                                <x-ui.empty-state
                                    title="Belum ada peserta"
                                    description="Pendaftaran pertama akan muncul otomatis di sini."
                                    action-label="Buka landing page"
                                    :action-href="route('landing')"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-ui.table>

            <div class="mt-6">
                {{ $participants->links() }}
            </div>
        </x-ui.card>
    </div>
</x-app-layout>
