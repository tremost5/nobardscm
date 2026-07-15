<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Panitia', 'href' => route('admin.panitia.index')], ['label' => 'Tambah Panitia']]" />
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-ui.card>
                <h1 class="font-display text-2xl font-semibold text-slate-950">Tambah Panitia Baru</h1>
                <p class="mt-1 text-sm text-slate-500">Daftarkan panitia untuk melakukan check-in peserta acara</p>

                <form method="POST" action="{{ route('admin.panitia.store') }}" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-900">Nama Panitia</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20" placeholder="Masukkan nama panitia">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-900">Nomor WhatsApp</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20" placeholder="Masukkan nomor WhatsApp panitia">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4 border-t border-slate-200 pt-6">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-500 px-6 py-3 font-semibold text-slate-950 shadow-lg shadow-amber-400/25 transition hover:brightness-105">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            Simpan Panitia
                        </button>
                        <a href="{{ route('admin.panitia.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 hover:bg-slate-50">
                            Batal
                        </a>
                    </div>
                </form>
            </x-ui.card>
        </div>

        <div>
            <x-ui.card>
                <h3 class="font-semibold text-slate-950">Informasi</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Panitia yang dibuat di sini akan mendapatkan akses ke menu check-in peserta. Mereka dapat melakukan check-in melalui QR code atau manual.
                </p>
                <p class="mt-4 text-sm leading-6 text-slate-600">
                    Pastikan password yang diberikan sudah diberitahukan kepada panitia yang bersangkutan.
                </p>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
