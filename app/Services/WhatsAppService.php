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
        private readonly RegistrationMessageBuilder $messageBuilder,
        private readonly TicketService $ticketService,
    ) {
    }

    public function sendRegistrationConfirmation(Registration $registration, string $eventTitle, ?string $eventLocation = null, ?string $messageOverride = null): bool
    {
        try {
            $message = $messageOverride ?? $this->buildRegistrationMessage($registration, $eventTitle, $eventLocation);

            return $this->sendMessage($registration, $message);
        } catch (\Throwable $throwable) {
            Log::warning('Registration WhatsApp confirmation could not be sent.', [
                'registration_id' => $registration->id,
                'error' => $throwable->getMessage(),
            ]);

            return false;
        }
    }

    public function sendTestMessage(string $target, string $message = 'Test connection from DSCM Event.'): bool
    {
        return $this->sendMessageToTarget($target, $message);
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

        return $this->sendMessageToTarget($panitia->phone, $message);
    }

    public function resendRegistrationWhatsApp(Registration $registration, string $eventTitle, ?string $eventLocation = null, ?string $messageOverride = null): bool
    {
        try {
            $message = $messageOverride ?? $this->buildRegistrationMessage($registration, $eventTitle, $eventLocation);

            return $this->sendMessage($registration, $message);
        } catch (\Throwable $throwable) {
            Log::warning('Resend WhatsApp could not be completed.', [
                'registration_id' => $registration->id,
                'error' => $throwable->getMessage(),
            ]);

            return false;
        }
    }

    private function sendMessage(Registration $registration, string $message): bool
    {
        $target = $registration->whatsapp_number;

        if (! $this->settingsService->boolean('whatsapp_enabled', true)) {
            Log::info('WhatsApp sending skipped because the service is disabled.', [
                'target' => $target,
            ]);

            $registration->forceFill([
                'wa_status' => 'failed',
                'wa_error' => 'WhatsApp service is disabled.',
                'wa_retry_count' => (int) $registration->wa_retry_count + 1,
            ])->save();

            return false;
        }

        $token = $this->settingsService->string('fonnte_token');
        if ($token === '') {
            Log::warning('WhatsApp settings are incomplete.', [
                'target' => $target,
            ]);

            $registration->forceFill([
                'wa_status' => 'failed',
                'wa_error' => 'WhatsApp settings are incomplete.',
                'wa_retry_count' => (int) $registration->wa_retry_count + 1,
            ])->save();

            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()
                ->connectTimeout(5)
                ->timeout(10)
                ->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                ]);

            if ($response->successful()) {
                $this->updateRegistrationDeliveryStatus($registration, 'sent');

                return true;
            }

            $error = 'WhatsApp request returned an unexpected response.';
            Log::warning($error, [
                'target' => $target,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $this->updateRegistrationDeliveryStatus($registration, 'failed', $error);
        } catch (\Throwable $throwable) {
            $error = $throwable->getMessage();
            Log::warning('WhatsApp confirmation could not be sent.', [
                'target' => $target,
                'error' => $error,
            ]);

            $this->updateRegistrationDeliveryStatus($registration, 'failed', $error);
        }

        return false;
    }

    private function sendMessageToTarget(string $target, string $message): bool
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
            ])->asForm()
                ->connectTimeout(5)
                ->timeout(10)
                ->post('https://api.fonnte.com/send', [
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
        return $this->messageBuilder->build(
            $registration,
            $registration->registration_number,
            $this->ticketService->generateTicketUrl($registration->ticket_token),
        );
    }

    private function updateRegistrationDeliveryStatus(Registration $registration, string $status, ?string $error = null): void
    {
        try {
            $payload = ['wa_status' => $status];

            if ($status === 'sent') {
                $payload['wa_sent_at'] = now();
                $payload['wa_error'] = null;
            } else {
                $payload['wa_error'] = $error;
                $payload['wa_retry_count'] = (int) $registration->wa_retry_count + 1;
            }

            $registration->forceFill($payload)->save();
        } catch (\Throwable $throwable) {
            Log::warning('Registration WhatsApp status could not be updated.', [
                'registration_id' => $registration->id,
                'error' => $throwable->getMessage(),
            ]);
        }
    }
}
