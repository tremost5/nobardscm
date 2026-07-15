<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Panitia']]" />
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-semibold text-slate-950">Daftar Panitia</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola panitia yang dapat melakukan check-in peserta</p>
            </div>
            <a href="{{ route('admin.panitia.create') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-500 px-6 py-3 font-semibold text-slate-950 shadow-lg shadow-amber-400/25 transition hover:brightness-105">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah Panitia
            </a>
        </div>

        <x-ui.card>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Username</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Nomor WhatsApp</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Last Login</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($panitiaList as $panitia)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-6 py-4 font-semibold text-slate-950">{{ $panitia->name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $panitia->username }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $panitia->phone ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($panitia->force_password_change)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">
                                            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                            Belum Aktivasi
                                        </span>
                                    @elseif ($panitia->status === 'aktif')
                                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">
                                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                            Sudah Aktivasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">
                                            <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $panitia->last_login_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.panitia.edit', $panitia) }}" class="inline-flex items-center gap-2 rounded-full border border-brand-300 bg-brand-50 px-4 py-2 text-sm font-semibold text-brand-700 hover:bg-brand-100">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.panitia.reset-password', $panitia) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100">
                                                Reset Password
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.panitia.destroy', $panitia) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-red-300 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <x-ui.empty-state
                                        title="Belum ada panitia"
                                        description="Tambahkan panitia untuk mulai melakukan check-in peserta."
                                        action-label="Tambah Panitia"
                                        :action-href="route('admin.panitia.create')"
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($panitiaList->hasPages())
                <div class="mt-6 border-t border-slate-200 pt-6">
                    {{ $panitiaList->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
