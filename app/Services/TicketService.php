<?php

namespace App\Services;

use App\Models\Registration;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketService
{
    public function generateForRegistration(Registration $registration): array
    {
        $registrationNumber = $this->generateRegistrationNumber($registration);
        $token = $this->generateToken($registration);
        $ticketUrl = $this->generateTicketUrl($token);

        return [
            'registration_number' => $registrationNumber,
            'ticket_token' => $token,
            'ticket_url' => $ticketUrl,
        ];
    }

    public function generateRegistrationNumber(Registration $registration): string
    {
        $year = now()->year;
        $count = Registration::query()->count() + 1;

        return sprintf('NBR%d-%05d', $year, $count);
    }

    public function generateToken(Registration $registration): string
    {
        return Str::random(40);
    }

    public function generateTicketUrl(string $token): string
    {
        return url('/ticket/'.$token);
    }

    public function generateQrCodeSvg(string $content): string
    {
        return QrCode::size(220)->generate($content);
    }

    public function generatePdf(Registration $registration): string
    {
        $html = view('ticket.pdf', [
            'registration' => $registration,
            'qrCode' => $this->generateQrCodeSvg($this->generateTicketUrl($registration->ticket_token)),
        ])->render();

        return Pdf::loadHTML($html)->setPaper('a5', 'portrait')->output();
    }
}
