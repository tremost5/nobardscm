<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function __construct(
        private readonly SettingsService $settingsService,
    ) {
    }

    public function sendRegistrationConfirmation(Registration $registration, string $eventTitle, ?string $eventLocation = null, ?string $messageOverride = null): bool
    {
        $message = $messageOverride ?? $this->buildRegistrationMessage($registration, $eventTitle, $eventLocation);

        return $this->sendMessage($registration->whatsapp_number, $message);
    }

    public function sendTestMessage(string $target, string $message = 'Test connection from DSCM Event.'): bool
    {
        return $this->sendMessage($target, $message);
    }

    public function sendPanitiaCredentials(User $panitia, string $temporaryPassword, string $loginUrl): bool
    {
        $message = implode("\n", array_filter([
            'Halo '.$panitia->name.',',
            '',
            'Akun panitia Anda telah dibuat.',
            'Username: '.$panitia->username,
            'Password sementara: '.$temporaryPassword,
            'Login: '.$loginUrl,
            '',
            'Harap segera ganti password setelah login pertama.',
            '',
            'Tuhan Yesus Memberkati.',
        ]));

        if (blank($panitia->phone)) {
            Log::warning('Panitia WhatsApp credentials skipped because no phone number is available.', [
                'panitia_id' => $panitia->id,
            ]);

            return false;
        }

        return $this->sendMessage($panitia->phone, $message);
    }

    private function sendMessage(string $target, string $message): bool
    {
        if (! $this->settingsService->boolean('whatsapp_enabled', true)) {
            Log::info('WhatsApp sending skipped because the service is disabled.', [
                'target' => $target,
            ]);

            return false;
        }

        $token = $this->settingsService->string('fonnte_token');
        if ($token === '') {
            Log::warning('WhatsApp settings are incomplete.', [
                'target' => $target,
            ]);

            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning('WhatsApp request returned an unexpected response.', [
                'target' => $target,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $throwable) {
            Log::warning('WhatsApp confirmation could not be sent.', [
                'target' => $target,
                'error' => $throwable->getMessage(),
            ]);
        }

        return false;
    }

    private function buildRegistrationMessage(Registration $registration, string $eventTitle, ?string $eventLocation = null): string
    {
        $snackLine = $registration->bring_snack
            ? 'Anda memilih untuk membawa makanan / camilan.'
            : 'Anda tidak memilih untuk membawa makanan / camilan.';

        return implode("\n", array_filter([
            'Pendaftaran berhasil, '.$registration->full_name.'.',
            '',
            'Terima kasih sudah mendaftar untuk '.$eventTitle.'.',
            $eventLocation ? 'Lokasi acara: '.$eventLocation : null,
            'Gereja asal: '.$registration->church_name,
            'Nomor WhatsApp: '.$registration->whatsapp_number,
            $snackLine,
            '',
            'Sampai bertemu di acara.',
        ]));
    }
}
