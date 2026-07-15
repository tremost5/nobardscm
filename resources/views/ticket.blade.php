<x-landing-layout title="Ticket">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(251,191,36,0.16),_transparent_24%),linear-gradient(135deg,_#020617_0%,_#07111f_60%,_#030712_100%)] px-4 py-10 text-white sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-6xl flex-col gap-6 lg:flex-row">
            <div class="w-full overflow-hidden rounded-[2rem] border border-white/10 bg-white/10 p-4 shadow-[0_30px_110px_rgba(2,6,23,0.5)] backdrop-blur-xl lg:w-[45%]">
                <img src="{{ asset('logo.jpeg') }}" alt="Official poster" class="h-full min-h-[420px] w-full rounded-[1.5rem] object-cover">
            </div>

            <div class="w-full rounded-[2rem] border border-white/10 bg-slate-950/80 p-6 shadow-[0_30px_120px_rgba(2,6,23,0.6)] backdrop-blur-xl lg:w-[55%]">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-200">Ticket</p>
                        <h1 class="mt-2 font-display text-3xl font-semibold text-white">{{ $registration->registration_number }}</h1>
                    </div>
                    <a href="{{ route('ticket.download', $registration->ticket_token) }}" class="rounded-full border border-amber-300/30 bg-amber-400/15 px-4 py-2 text-sm font-semibold text-amber-100">Download PDF</a>
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Nama</p>
                        <p class="mt-3 text-lg font-semibold text-white">{{ $registration->full_name }}</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Gereja</p>
                        <p class="mt-3 text-lg font-semibold text-white">{{ $registration->church_name }}</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">WhatsApp</p>
                        <p class="mt-3 text-lg font-semibold text-white">{{ $registration->whatsapp_number }}</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Bawa Snack</p>
                        <p class="mt-3 text-lg font-semibold text-white">{{ $registration->bring_snack_text }}</p>
                    </div>
                </div>

                <div class="mt-8 rounded-[1.5rem] border border-white/10 bg-white/5 p-4">
                    <div class="flex items-center justify-center">
                        {!! $qrCode !!}
                    </div>
                    <p class="mt-4 text-center text-sm text-slate-300">Tunjukan QR Code ini ke panitia untuk check-in.</p>
                </div>
            </div>
        </div>
    </div>
</x-landing-layout>
