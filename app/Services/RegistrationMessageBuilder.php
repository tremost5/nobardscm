<?php

namespace App\Services;

use App\Models\Registration;

class RegistrationMessageBuilder
{
    public function build(Registration $registration, string $registrationNumber, string $ticketUrl): string
    {
        return implode("\n", [
            '🎉 Pendaftaran berhasil!',
            '',
            'Shalom, '.$registration->full_name.'.',
            '',
            'Terima kasih sudah mendaftar untuk:',
            '⚽ Nobar Final Piala Dunia 2026',
            '',
            '📍 Lokasi',
            'DSCM Main Hall',
            '',
            '⛪ Gereja Asal',
            $registration->church_name,
            '',
            '📱 Nomor WhatsApp',
            $registration->whatsapp_number,
            '',
            '🍿 Membawa makanan/camilan',
            $registration->bring_snack_text,
            '',
            '🎫 Nomor Registrasi',
            $registrationNumber,
            '',
            '🎟 Tiket & QR Code',
            $ticketUrl,
            '',
            'Sampai bertemu di Auditorium NICC.',
            '',
            'Tuhan Yesus Memberkati.',
        ]);
    }
}
