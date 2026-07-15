<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs
            :items="[
                ['label' => 'Dashboard', 'href' => route('dashboard')],
                ['label' => 'Check-in Manual'],
            ]"
        />
    </x-slot>

    <div class="space-y-6">
        <x-ui.card>
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Check-in Manual</p>
                    <h1 class="mt-2 font-display text-2xl font-semibold text-slate-950">Cari peserta dan lakukan check-in</h1>
                    <p class="mt-2 text-sm text-slate-600">Gunakan nama, nomor WhatsApp, atau nomor registrasi untuk menemukan peserta.</p>
                </div>
            </div>

            <div class="mt-8 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                <label for="search" class="block text-sm font-semibold text-slate-900">Pencarian peserta</label>
                <input id="search" type="text" placeholder="Ketik minimal 2 karakter..." class="mt-3 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
            </div>

            <div id="search-results" class="mt-6 space-y-4 overflow-visible pb-2"></div>
        </x-ui.card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('search');
            const results = document.getElementById('search-results');
            let timeout;

            input.addEventListener('input', function () {
                const value = this.value.trim();
                clearTimeout(timeout);

                if (value.length < 2) {
                    results.innerHTML = '';
                    return;
                }

                timeout = setTimeout(async () => {
                    try {
                        const response = await fetch(`{{ route('attendance.manual-checkin.search') }}?q=${encodeURIComponent(value)}`);
                        const data = await response.json();
                        const items = data.results || [];

                        if (!items.length) {
                            results.innerHTML = '<div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500">Tidak ada hasil.</div>';
                            return;
                        }

                        results.innerHTML = items.map(item => `
                            <div class="overflow-visible rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-slate-950">${item.full_name}</p>
                                        <p class="mt-1 text-sm text-slate-600">${item.church_name || '-'}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.3em] text-slate-400">${item.registration_number || '-'}</p>
                                    </div>
                                    <div class="text-sm text-slate-500">
                                        <p>WhatsApp: ${item.whatsapp_number || '-'}</p>
                                        <p>Snack: ${item.bring_snack || '-'}</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex flex-wrap items-center gap-3">
                                    <span class="rounded-full ${item.hasAttended ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'} px-3 py-1 text-sm font-semibold">
                                        ${item.hasAttended ? 'Sudah Hadir' : 'Belum Hadir'}
                                    </span>
                                    ${item.hasAttended ? `<span class="text-sm text-slate-500">${item.attendanceData?.method || '-'} • ${item.attendanceData?.checked_in_at || '-'}</span>` : ''}
                                </div>
                                ${item.hasAttended ? `<div class="mt-5 flex w-full items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 sm:justify-start">
                                    ✓ Sudah Hadir
                                </div>` : `<div class="mt-5">
                                    <button type="button" class="flex min-h-[48px] w-full items-center justify-center rounded-2xl bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-500 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-amber-400/30 transition duration-200 hover:brightness-105 hover:shadow-xl active:translate-y-[1px] disabled:cursor-not-allowed disabled:opacity-60" data-id="${item.id}">
                                        Hadir
                                    </button>
                                </div>`}
                            </div>
                        `).join('');

                        results.querySelectorAll('button[data-id]').forEach(button => {
                            button.addEventListener('click', async () => {
                                const id = button.getAttribute('data-id');
                                const response = await fetch('{{ route('attendance.scan-qr.checkin') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    },
                                    body: JSON.stringify({ registration_id: id, method: 'manual' }),
                                });
                                const data = await response.json();
                                if (data.success) {
                                    button.closest('div.rounded-3xl').innerHTML = '<div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">Check-in berhasil.</div>';
                                } else {
                                    button.closest('div.rounded-3xl').innerHTML = `<div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">${data.message || 'Gagal check-in'}</div>`;
                                }
                            });
                        });
                    } catch (error) {
                        results.innerHTML = '<div class="rounded-2xl border border-red-200 bg-red-50 p-6 text-sm text-red-700">Gagal memuat hasil pencarian.</div>';
                    }
                }, 300);
            });
        });
    </script>
</x-app-layout>
