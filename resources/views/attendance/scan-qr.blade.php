<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Scan QR'],
        ]" />
    </x-slot>

    <div x-data="qrScanner()" x-init="init()" class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
            <div>
                <x-ui.card class="p-0">
                    <div class="relative overflow-hidden rounded-3xl bg-slate-950">
                        <div id="qr-reader" class="min-h-[24rem] w-full"></div>

                        <div class="pointer-events-none absolute inset-0 flex items-center justify-center" x-show="!cameraActive">
                            <div class="rounded-2xl border border-white/20 bg-slate-950/80 px-5 py-4 text-center text-sm text-white shadow-lg">
                                <p class="font-semibold">Kamera belum aktif</p>
                                <p class="mt-1 text-slate-300">Tekan tombol mulai kamera untuk memulai scan QR.</p>
                            </div>
                        </div>

                        <div class="absolute inset-x-0 top-4 flex justify-center gap-3 px-4">
                            <button
                                type="button"
                                @click="startCamera()"
                                class="rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-400"
                            >
                                Mulai Kamera
                            </button>
                            <button
                                type="button"
                                @click="switchCamera()"
                                class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20"
                            >
                                Ganti Kamera
                            </button>
                        </div>

                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-950 to-transparent p-6 text-white">
                            <p class="text-sm font-semibold" x-text="statusMessage"></p>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <div>
                <x-ui.card>
                    <h3 class="font-semibold text-slate-950">Hasil Scan</h3>

                    <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Status kamera</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900" x-text="statusMessage"></p>
                    </div>

                    <div x-show="!scanned && !hasError" class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-white p-4 text-sm text-slate-600">
                        Menunggu scan QR code dari kamera.
                    </div>

                    <div x-show="scanned && !hasError" class="mt-6 space-y-4">
                        <template x-if="scanData">
                            <div>
                                <div class="rounded-2xl bg-emerald-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-700">Peserta</p>
                                    <p class="mt-3 font-semibold text-slate-950" x-text="scanData.registration.full_name"></p>
                                    <p class="mt-1 text-sm text-slate-600" x-text="scanData.registration.church_name"></p>
                                    <p class="mt-2 text-xs text-slate-500" x-text="'No. ' + scanData.registration.registration_number"></p>
                                </div>

                                <div x-show="!scanData.hasAttended" class="mt-4 space-y-3">
                                    <button
                                        @click="confirmCheckin()"
                                        type="button"
                                        class="w-full rounded-full bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700"
                                    >
                                        Check-in
                                    </button>
                                    <button
                                        @click="resetScan()"
                                        type="button"
                                        class="w-full rounded-full border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 hover:bg-slate-50"
                                    >
                                        Scan QR Lain
                                    </button>
                                </div>

                                <div x-show="scanData.hasAttended" class="mt-4 rounded-2xl border border-yellow-300 bg-yellow-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-700">Sudah Hadir</p>
                                    <p class="mt-2 text-sm text-yellow-900">Peserta ini sudah melakukan check-in</p>
                                    <p class="mt-1 text-xs text-yellow-700" x-text="'Jam: ' + scanData.attendanceData.checked_in_at"></p>
                                    <button
                                        @click="resetScan()"
                                        type="button"
                                        class="mt-4 w-full rounded-full border border-yellow-300 bg-white px-6 py-2 text-sm font-semibold text-yellow-700 hover:bg-yellow-50"
                                    >
                                        Scan QR Lain
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="hasError" class="mt-6 rounded-2xl border border-red-300 bg-red-50 p-4">
                        <p class="text-sm font-semibold text-red-900" x-text="errorMessage"></p>
                        <button
                            @click="startCamera()"
                            type="button"
                            class="mt-4 w-full rounded-full border border-red-300 bg-white px-6 py-2 text-sm font-semibold text-red-700 hover:bg-red-50"
                        >
                            Coba Lagi
                        </button>
                    </div>
                </x-ui.card>
            </div>
        </div>

        <div x-show="showConfirmation" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" x-transition>
            <x-ui.card class="w-full max-w-md">
                <h3 class="font-display text-lg font-semibold text-slate-950">Konfirmasi Check-in</h3>

                <div class="mt-6 space-y-4" x-show="scanData">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-600">Nama</p>
                        <p class="mt-2 font-semibold text-slate-950" x-text="scanData?.registration.full_name"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-600">Gereja</p>
                        <p class="mt-2 font-semibold text-slate-950" x-text="scanData?.registration.church_name"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-600">Status</p>
                        <p class="mt-2 font-semibold text-slate-950">Belum Hadir</p>
                    </div>
                </div>

                <p class="mt-6 font-semibold text-slate-950">Yakin peserta ini hadir?</p>

                <div class="mt-6 flex gap-4">
                    <button
                        @click="showConfirmation = false"
                        type="button"
                        class="flex-1 rounded-full border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Batal
                    </button>
                    <button
                        @click="submitCheckin()"
                        type="button"
                        class="flex-1 rounded-full bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700"
                    >
                        Ya, Check In
                    </button>
                </div>
            </x-ui.card>
        </div>
    </div>

    <script src="{{ asset('vendor/html5-qrcode/html5-qrcode.min.js') }}"></script>
    <script>
        function qrScanner() {
            return {
                scanned: false,
                hasError: false,
                errorMessage: '',
                statusMessage: 'Siap memulai kamera.',
                scanData: null,
                showConfirmation: false,
                cameraActive: false,
                scanner: null,
                facingMode: 'environment',

                async init() {
                    this.statusMessage = 'Menyiapkan kamera...';
                    await this.startCamera();
                },

                async startCamera() {
                    if (!window.Html5Qrcode) {
                        this.showError('Library QR Scanner gagal dimuat.');
                        return;
                    }

                    if (!window.isSecureContext && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                        this.showError('Kamera hanya tersedia pada HTTPS atau localhost.');
                        return;
                    }

                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        this.showError('Browser tidak mengizinkan akses kamera. Gunakan HTTPS atau browser yang mendukung.');
                        return;
                    }

                    this.hasError = false;
                    this.errorMessage = '';

                    try {
                        if (this.scanner) {
                            await this.scanner.stop();
                        }
                    } catch (error) {
                        console.warn('Scanner stop warning:', error);
                    }

                    const readerElement = document.getElementById('qr-reader');
                    if (!readerElement) {
                        this.showError('Area pemindai tidak tersedia.');
                        return;
                    }

                    this.scanner = new window.Html5Qrcode('qr-reader');

                    const config = {
                        fps: 10,
                        qrbox: { width: 260, height: 260 },
                        aspectRatio: 1.5,
                    };

                    try {
                        await this.scanner.start(
                            { facingMode: this.facingMode },
                            config,
                            (decodedText) => {
                                this.handleQRCode(decodedText);
                            },
                            () => {}
                        );

                        this.cameraActive = true;
                        this.statusMessage = 'Kamera aktif — arahkan QR code ke layar.';
                    } catch (error) {
                        this.handleScanError(error);
                    }
                },

                async switchCamera() {
                    this.facingMode = this.facingMode === 'environment' ? 'user' : 'environment';
                    await this.startCamera();
                },

                handleScanError(error) {
                    console.error('QR Reader start error:', error);
                    this.cameraActive = false;
                    this.showError(this.getFriendlyError(error));
                },

                getFriendlyError(error) {
                    const message = error?.message || '';
                    const name = error?.name || '';

                    if (name === 'NotAllowedError' || message.includes('Permission')) {
                        return 'Izin kamera ditolak. Silakan izinkan akses kamera dan coba lagi.';
                    }

                    if (name === 'NotFoundError' || message.includes('device')) {
                        return 'Kamera tidak ditemukan. Pastikan perangkat Anda memiliki kamera.';
                    }

                    if (name === 'NotReadableError' || message.includes('in use')) {
                        return 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi lain lalu coba lagi.';
                    }

                    if (name === 'OverconstrainedError') {
                        return 'Kamera yang dipilih tidak tersedia. Coba ganti kamera.';
                    }

                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        return 'Browser tidak mengizinkan akses kamera. Gunakan HTTPS atau browser yang mendukung.';
                    }

                    if (!window.isSecureContext && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                        return 'Kamera hanya tersedia pada HTTPS atau localhost.';
                    }

                    return message || 'Tidak dapat memulai kamera. Silakan coba lagi.';
                },

                showError(message) {
                    this.hasError = true;
                    this.errorMessage = message;
                    this.statusMessage = message;
                },

                async handleQRCode(token) {
                    if (this.scanned) return;

                    try {
                        this.scanned = true;
                        this.statusMessage = 'QR terdeteksi. Memproses data...';
                        const response = await fetch('{{ route("attendance.scan-qr.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ ticket_token: token }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.scanData = data;
                            this.hasError = false;
                            this.statusMessage = 'QR berhasil diproses.';
                        } else {
                            this.hasError = true;
                            this.errorMessage = data.message;
                            this.statusMessage = data.message;
                        }
                    } catch (error) {
                        this.hasError = true;
                        this.errorMessage = 'Terjadi kesalahan saat memproses QR code';
                        this.statusMessage = this.errorMessage;
                    }
                },

                confirmCheckin() {
                    this.showConfirmation = true;
                },

                async submitCheckin() {
                    try {
                        const response = await fetch('{{ route("attendance.scan-qr.checkin") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                registration_id: this.scanData.registration.id,
                                method: 'qr',
                            }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showConfirmation = false;
                            this.scanData.hasAttended = true;
                            this.statusMessage = 'Check-in berhasil.';
                        } else {
                            this.hasError = true;
                            this.errorMessage = data.message;
                            this.statusMessage = data.message;
                        }
                    } catch (error) {
                        this.hasError = true;
                        this.errorMessage = 'Terjadi kesalahan saat check-in';
                        this.statusMessage = this.errorMessage;
                    }
                },

                resetScan() {
                    this.scanned = false;
                    this.hasError = false;
                    this.errorMessage = '';
                    this.scanData = null;
                    this.statusMessage = 'Kamera aktif — arahkan QR code ke layar.';
                }
            };
        }
    </script>
</x-app-layout>
