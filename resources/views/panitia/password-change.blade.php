<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('attendance.dashboard')], ['label' => 'Ubah Password']]" />
    </x-slot>

    <div class="max-w-xl">
        <x-ui.card>
            <h1 class="font-display text-2xl font-semibold text-slate-950">Ubah Password</h1>
            <p class="mt-2 text-sm text-slate-600">Password Anda sedang berlaku sementara. Silakan buat password baru untuk melanjutkan.</p>

            <form method="POST" action="{{ route('panitia.password.change.update') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-900">Password Baru</label>
                    <input type="password" id="password" name="password" required class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20" placeholder="Masukkan password baru">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-900">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20" placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-500 px-6 py-3 font-semibold text-slate-950 shadow-lg shadow-amber-400/25 transition hover:brightness-105">
                    GANTI PASSWORD
                </button>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 hover:bg-slate-50">
                        Logout
                    </button>
                </form>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>
