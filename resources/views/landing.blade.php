<x-landing-layout :title="$brandName">
    <div
        x-data="registrationForm({
            storageKey: 'dscm-event.registration',
            submitUrl: '{{ route('registrations.store') }}',
            csrfToken: '{{ csrf_token() }}',
        })"
        class="landing-shell flex min-h-dvh flex-col items-center justify-center gap-3 overflow-hidden bg-[#050505] px-4 py-3 text-white sm:gap-[18px] sm:px-5 sm:py-5"
    >
        <div class="pointer-events-none absolute inset-0 -z-20 bg-[radial-gradient(circle_at_top,_#16213e_0%,_#0a1325_35%,_#050505_100%)]"></div>
        <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_center,_transparent_0%,_rgba(0,0,0,0.2)_70%,_rgba(0,0,0,0.38)_100%)]"></div>

        <div class="landing-poster-frame flex items-center justify-center rounded-[24px] border border-amber-200/20 bg-[linear-gradient(145deg,_rgba(255,215,0,0.08),_rgba(255,255,255,0.02))] p-[10px] shadow-[0_35px_80px_rgba(0,0,0,0.55)]">
            <img src="{{ asset('logo.jpeg') }}" alt="Official event poster" class="block h-[58vh] w-[320px] rounded-[20px] object-contain sm:h-[62vh] sm:w-[360px] lg:h-[68vh] lg:w-[390px]">
        </div>

        <x-countdown target="2026-07-20 00:00:00" />

        <button
            type="button"
            @click="openModal()"
            class="landing-register group relative mt-0 flex h-[56px] w-full max-w-[390px] items-center justify-center overflow-hidden rounded-full border border-amber-200/25 bg-[length:300%_300%] bg-[linear-gradient(90deg,_#ffb300_0%,_#ffd54f_35%,_#ffef9b_70%,_#ffb300_100%)] text-[20px] font-extrabold uppercase tracking-[0.24em] text-[#111] shadow-[0_0_20px_rgba(255,200,0,0.45)] transition duration-150 ease-out hover:scale-[1.03] hover:shadow-[0_0_28px_rgba(255,200,0,0.55)] active:scale-[0.98] sm:h-[60px]"
        >
            <span class="mr-3 text-[1.1rem] transition-transform duration-150 group-hover:scale-110">⚽</span>
            <span class="relative z-10">DAFTAR SEKARANG</span>
        </button>
        <div
            x-cloak
            x-show="open"
            x-transition.opacity
            @window:resize="$nextTick()"
            class="modal-backdrop fixed inset-0 z-40 flex px-4 py-6"
            :style="{
                'align-items': window.innerWidth < 768 ? 'flex-start' : 'center',
                'justify-content': 'center',
                'overflow-y': 'auto',
                'overflow-x': 'hidden'
            }"
        >
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md"></div>

            <div class="modal-card relative z-10 rounded-[2rem] border border-white/10 bg-slate-950/95 shadow-[0_30px_120px_rgba(2,6,23,0.65)]"
                 :style="{
                    'width': window.innerWidth < 768 ? 'calc(100vw - 32px)' : '100%',
                    'max-width': window.innerWidth < 768 ? 'none' : '48rem',
                    'max-height': window.innerWidth < 768 ? '92dvh' : 'none',
                    'padding-top': window.innerWidth < 768 ? 'env(safe-area-inset-top, 16px)' : '0',
                    'padding-bottom': window.innerWidth < 768 ? 'env(safe-area-inset-bottom, 16px)' : '0',
                    'overflow-y': window.innerWidth < 768 ? 'auto' : 'hidden',
                    'overflow-x': 'hidden',
                    'flex-shrink': '0'
                }">
                <div class="border-b border-white/10 bg-white/5 px-6 py-5 sm:px-8 md:sticky md:top-0">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-200">Registration form</p>
                            <h2 class="mt-2 font-display text-3xl font-semibold text-white">Daftar hadir ke event Nobar</h2>
                        </div>

                        <button type="button" @click="requestClose()" class="rounded-full border border-white/10 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:bg-white/10">Tutup</button>
                    </div>
                </div>

                <form @submit.prevent="submit()" class="space-y-6 px-6 py-6 sm:px-8">
                    <div class="space-y-5">
                        <div>
                            <label for="full_name" class="mb-2 block text-sm font-semibold text-slate-200">Nama Lengkap *</label>
                            <input
                                id="full_name"
                                type="text"
                                x-model="form.full_name"
                                @input="markTouched('full_name'); validateField('full_name')"
                                @blur="markTouched('full_name'); validateField('full_name')"
                                class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white shadow-sm outline-none transition placeholder:text-slate-500 focus:border-amber-300/40 focus:ring-4 focus:ring-amber-300/10"
                                placeholder="Nama lengkap"
                            >
                            <p x-show="fieldsTouched.full_name && errors.full_name" x-text="errors.full_name" class="mt-2 text-sm text-rose-300"></p>
                        </div>

                        <div>
                            <label for="church_name" class="mb-2 block text-sm font-semibold text-slate-200">Gereja Asal *</label>
                            <input
                                id="church_name"
                                type="text"
                                x-model="form.church_name"
                                @input="markTouched('church_name'); validateField('church_name')"
                                @blur="markTouched('church_name'); validateField('church_name')"
                                class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white shadow-sm outline-none transition placeholder:text-slate-500 focus:border-amber-300/40 focus:ring-4 focus:ring-amber-300/10"
                                placeholder="Nama gereja asal"
                            >
                            <p x-show="fieldsTouched.church_name && errors.church_name" x-text="errors.church_name" class="mt-2 text-sm text-rose-300"></p>
                        </div>

                        <div>
                            <label for="whatsapp_number" class="mb-2 block text-sm font-semibold text-slate-200">Nomor WhatsApp *</label>
                            <input
                                id="whatsapp_number"
                                type="tel"
                                inputmode="numeric"
                                x-model="form.whatsapp_number"
                                @input="markTouched('whatsapp_number'); validateField('whatsapp_number')"
                                @blur="markTouched('whatsapp_number'); validateField('whatsapp_number')"
                                class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white shadow-sm outline-none transition placeholder:text-slate-500 focus:border-amber-300/40 focus:ring-4 focus:ring-amber-300/10"
                                placeholder="6281234567890"
                            >
                            <p class="mt-2 text-xs text-slate-400">Gunakan nomor aktif agar konfirmasi WhatsApp bisa diterima.</p>
                            <p x-show="fieldsTouched.whatsapp_number && errors.whatsapp_number" x-text="errors.whatsapp_number" class="mt-2 text-sm text-rose-300"></p>
                        </div>

                        <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                            <p class="text-sm font-semibold text-slate-100">Bawa makanan / camilan untuk dinikmati bersama?</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <label class="flex cursor-pointer items-center gap-3 rounded-full border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-200 transition hover:bg-white/10">
                                    <input type="radio" name="bring_snack" value="1" x-model="form.bring_snack" class="border-white/20 bg-slate-900 text-amber-400 focus:ring-amber-300/20">
                                    Ya
                                </label>
                                <label class="flex cursor-pointer items-center gap-3 rounded-full border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-200 transition hover:bg-white/10">
                                    <input type="radio" name="bring_snack" value="0" x-model="form.bring_snack" class="border-white/20 bg-slate-900 text-amber-400 focus:ring-amber-300/20">
                                    Tidak
                                </label>
                            </div>
                        </div>

                        <p x-show="errors.form" x-text="errors.form" class="text-sm text-rose-300"></p>
                    </div>

                    <div class="space-y-4">
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="inline-flex w-full items-center justify-center gap-3 rounded-full border border-amber-200/30 bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-500 px-6 py-4 text-sm font-black uppercase tracking-[0.2em] text-slate-950 shadow-[0_18px_55px_rgba(217,164,31,0.42)] transition disabled:cursor-not-allowed disabled:opacity-70"
                        >
                            <span x-show="!submitting">Kirim Pendaftaran</span>
                            <span x-show="submitting">Memproses...</span>
                        </button>

                        <p class="text-center text-xs text-slate-400">Dengan mendaftar, Anda akan menerima tiket digital melalui WhatsApp.</p>

                        <button type="button" @click="requestClose()" class="inline-flex w-full items-center justify-center rounded-full border border-white/10 px-6 py-4 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div
            x-cloak
            x-show="confirmCloseOpen"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6"
        >
            <div class="absolute inset-0 bg-slate-950/85 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full max-w-lg rounded-[2rem] border border-white/10 bg-slate-950 p-6 shadow-[0_30px_100px_rgba(2,6,23,0.7)]">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-200">Konfirmasi</p>
                <h3 class="mt-3 font-display text-2xl font-semibold text-white">Data yang sudah Anda isi akan hilang.</h3>
                <p class="mt-3 text-sm leading-7 text-slate-300">Apakah Anda yakin ingin menutup formulir?</p>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button type="button" @click="keepEditing()" class="inline-flex flex-1 items-center justify-center rounded-full border border-white/10 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                        Tetap Isi
                    </button>
                    <button type="button" @click="confirmDiscard()" class="inline-flex flex-1 items-center justify-center rounded-full bg-rose-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-400">
                        Ya, Tutup
                    </button>
                </div>
            </div>
        </div>

        <div
            x-cloak
            x-show="successOpen"
            x-transition.opacity
            class="celebration-backdrop fixed inset-0 z-50 flex items-center justify-center px-4 py-6 overflow-hidden"
        >
            <div class="absolute inset-0 bg-slate-950/88 backdrop-blur-md"></div>
            
            <!-- Confetti particles -->
            <div class="pointer-events-none absolute inset-0">
                <div class="confetti-particle" style="left: 10%; top: 20%;"></div>
                <div class="confetti-particle" style="left: 20%; top: 15%;"></div>
                <div class="confetti-particle" style="left: 30%; top: 25%;"></div>
                <div class="confetti-particle" style="left: 70%; top: 20%;"></div>
                <div class="confetti-particle" style="left: 80%; top: 15%;"></div>
                <div class="confetti-particle" style="left: 90%; top: 25%;"></div>
            </div>

            <div class="celebration-card relative z-10 w-full max-w-md rounded-[2.5rem] border border-emerald-300/30 bg-gradient-to-b from-slate-900 to-slate-950 p-8 sm:p-10 shadow-[0_40px_160px_rgba(16,185,129,0.15)]">
                <!-- Success icon with glow -->
                <div class="flex flex-col items-center">
                    <div class="success-icon-glow absolute h-32 w-32 rounded-full opacity-75"></div>
                    <div class="success-icon relative inline-flex h-24 w-24 items-center justify-center rounded-full border-2 border-emerald-400 bg-emerald-400/10 text-5xl font-bold text-emerald-300">
                        ✓
                    </div>
                </div>

                <!-- Title -->
                <h2 class="mt-8 text-center font-display text-3xl font-bold text-white sm:text-4xl">Pendaftaran Berhasil!</h2>

                <!-- Divider -->
                <div class="mx-auto mt-6 h-px w-20 bg-gradient-to-r from-transparent via-emerald-400/50 to-transparent"></div>

                <!-- Body text -->
                <div class="mt-7 space-y-4 text-center text-sm sm:text-base leading-8 text-slate-300">
                    <p>Terima kasih, <span class="font-semibold text-emerald-300" x-text="registeredName"></span>.</p>
                    <p>Pendaftaran Anda berhasil.</p>
                    <p>Tiket digital telah dikirim ke WhatsApp Anda.</p>
                    <p class="pt-2">Sampai bertemu di Nobar Final Piala Dunia 2026.</p>
                    <p class="italic text-emerald-200">Tuhan Yesus Memberkati.</p>
                </div>

                <!-- Buttons -->
                <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:gap-4">
                    <button type="button" @click="closeSuccess()" class="flex-1 rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10 hover:border-white/30">
                        Tutup
                    </button>
                    <a :href="ticketUrl || '#'" target="_blank" class="flex-1 inline-flex items-center justify-center rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:shadow-xl hover:shadow-emerald-500/50 hover:from-emerald-400 hover:to-teal-400">
                        Lihat Tiket
                    </a>
                </div>

                <!-- Footer message -->
                <p class="mt-6 text-center text-xs text-slate-400">Tiket digital telah dikirim ke WhatsApp Anda dan dapat dibuka kembali kapan saja melalui tautan WhatsApp.</p>
            </div>
        </div>

    </div>
</x-landing-layout>
