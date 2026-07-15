<x-landing-layout :title="'Registration Successful'">
    <section class="section-shell flex min-h-screen items-center py-10">
        <div class="mx-auto w-full max-w-3xl">
            <x-ui.card class="relative overflow-hidden">
                <div class="absolute -right-10 -top-10 h-36 w-36 rounded-full bg-emerald-400/20 blur-3xl"></div>
                <div class="relative text-center">
                    <x-ui.badge tone="emerald">Registration Successful</x-ui.badge>

                    <h1 class="mt-6 font-display text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                        Thank you, {{ $registration->full_name }}.
                    </h1>

                    <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-slate-600">
                        Your registration has been saved and a WhatsApp confirmation has been prepared. We’re excited to welcome you to {{ $event->title }}.
                    </p>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Reminder</p>
                            <p class="mt-3 text-sm font-semibold text-slate-800">{{ $event->date_range_label }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Location</p>
                            <p class="mt-3 text-sm font-semibold text-slate-800">{{ $event->location ?: 'To be announced' }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Church</p>
                            <p class="mt-3 text-sm font-semibold text-slate-800">{{ $registration->church_name }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap justify-center gap-3">
                        <x-ui.button :href="route('landing')">Back to Home</x-ui.button>
                        <x-ui.button :href="route('dashboard')" variant="secondary">Admin Participants</x-ui.button>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </section>
</x-landing-layout>
