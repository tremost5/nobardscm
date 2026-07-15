<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <x-ui.alert :type="session('test_status') ? (session('test_status') === 'connected' ? 'success' : 'warning') : 'success'">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <span>{{ session('status') }}</span>
                    @if (session('test_status'))
                        <x-ui.badge :tone="session('test_status') === 'connected' ? 'emerald' : 'rose'">
                            {{ session('test_status') === 'connected' ? 'Connected' : 'Disconnected' }}
                        </x-ui.badge>
                    @endif
                </div>
            </x-ui.alert>
        @endif

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card label="WhatsApp" :value="$settings['whatsapp_enabled'] ? 'Aktif' : 'Nonaktif'" hint="Toggle status pengiriman." />
            <x-ui.stat-card label="Nomor device" :value="$settings['fonnte_number'] !== '' ? $settings['fonnte_number'] : 'Belum diisi'" hint="Nomor tujuan test connection." />
            <x-ui.stat-card label="Token" :value="$settings['fonnte_token'] !== '' ? 'Tersimpan' : 'Belum ada'" hint="Disimpan aman di database." />
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <x-ui.card>
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">WhatsApp Setting</p>
                    <h1 class="mt-2 font-display text-3xl font-semibold text-slate-950">Konfigurasi Fonnte</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Semua data disimpan di tabel settings agar mudah dipindahkan ke sistem CMS berikutnya.</p>
                </div>

                <form method="POST" action="{{ route('dashboard.whatsapp-settings.update') }}" class="mt-8 space-y-5">
                    @csrf

                    <x-ui.input
                        label="Sender Name"
                        name="sender_name"
                        type="text"
                        value="{{ old('sender_name', $settings['sender_name']) }}"
                        placeholder="DSCM Event"
                        hint="Nama sender yang akan digunakan untuk pengiriman WhatsApp."
                        :error="$errors->first('sender_name')"
                    />

                    <x-ui.input
                        label="Device Number"
                        name="fonnte_number"
                        type="text"
                        value="{{ old('fonnte_number', $settings['fonnte_number']) }}"
                        placeholder="6281234567890"
                        hint="Nomor tujuan test connection, gunakan format internasional tanpa tanda +."
                        :error="$errors->first('fonnte_number')"
                        required
                    />

                    <x-ui.input
                        label="Fonnte Token"
                        name="fonnte_token"
                        type="text"
                        value="{{ old('fonnte_token', $settings['fonnte_token']) }}"
                        placeholder="Masukkan token Fonnte"
                        :error="$errors->first('fonnte_token')"
                        required
                    />

                    <x-ui.select
                        label="Status"
                        name="whatsapp_enabled"
                        :error="$errors->first('whatsapp_enabled')"
                    >
                        <option value="1" @selected(old('whatsapp_enabled', (string) (int) $settings['whatsapp_enabled']) === '1')>Enable</option>
                        <option value="0" @selected(old('whatsapp_enabled', (string) (int) $settings['whatsapp_enabled']) === '0')>Disable</option>
                    </x-ui.select>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <x-ui.button type="submit">Save</x-ui.button>
                    </div>
                </form>
            </x-ui.card>

            <x-ui.card>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Test Connection</p>
                        <h2 class="mt-2 font-display text-2xl font-semibold text-slate-950">Kirim pesan uji</h2>
                    </div>
                    <x-ui.badge tone="amber">Safe check</x-ui.badge>
                </div>

                <p class="mt-4 text-sm leading-7 text-slate-600">
                    Klik tombol di bawah untuk mengirim pesan uji ke nomor device yang sudah tersimpan. Ini membantu memastikan token dan koneksi aktif sebelum event dimulai.
                </p>

                <form method="POST" action="{{ route('dashboard.whatsapp-settings.test') }}" class="mt-6">
                    @csrf
                    <x-ui.button type="submit" class="w-full">Test Connection</x-ui.button>
                </form>

                <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Notes</p>
                    <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                        <li>- Settings are stored in the database, not in `.env`.</li>
                        <li>- The registration flow uses the same service for confirmation messages.</li>
                        <li>- The poster path is also stored in `settings` for easy replacement later.</li>
                    </ul>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
