<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('attendance.dashboard')], ['label' => 'Profil']]"></x-ui.breadcrumbs>
    </x-slot>

    <div class="space-y-6">
        <x-ui.card>
            <h1 class="font-display text-2xl font-semibold text-slate-950">Profil Panitia</h1>
            <p class="mt-2 text-sm text-slate-500">Informasi akun Anda akan muncul di sini.</p>
        </x-ui.card>
    </div>
</x-app-layout>
