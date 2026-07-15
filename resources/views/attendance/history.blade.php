<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('attendance.dashboard')], ['label' => 'Riwayat Saya']]"></x-ui.breadcrumbs>
    </x-slot>

    <div class="space-y-6">
        <x-ui.card>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="font-display text-2xl font-semibold text-slate-950">Riwayat Saya</h1>
                    <p class="mt-2 text-sm text-slate-500">Semua check-in yang Anda lakukan akan muncul di sini.</p>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Waktu</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Peserta</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Gereja</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">No. Registrasi</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Metode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($entries as $entry)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-4 text-sm text-slate-600">{{ $entry->checked_in_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-4 py-4 font-semibold text-slate-950">{{ $entry->registration?->full_name ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-slate-600">{{ $entry->registration?->church_name ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-slate-600">{{ $entry->registration?->registration_number ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <x-ui.badge :tone="$entry->checkin_method === 'qr' ? 'brand' : 'amber'">
                                        {{ $entry->checkin_method === 'qr' ? 'QR Scan' : 'Manual' }}
                                    </x-ui.badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-sm text-slate-500">Belum ada check-in yang Anda lakukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($entries->hasPages())
                <div class="mt-6 border-t border-slate-200 pt-6">
                    {{ $entries->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
