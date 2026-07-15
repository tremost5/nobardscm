<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\TicketService;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function show(string $token, TicketService $ticketService): View|Response
    {
        $registration = Registration::query()->where('ticket_token', $token)->firstOrFail();

        if ($registration->ticket_token !== $token) {
            abort(404);
        }

        return view('ticket', [
            'registration' => $registration,
            'ticketUrl' => $ticketService->generateTicketUrl($registration->ticket_token),
            'qrCode' => $ticketService->generateQrCodeSvg($ticketService->generateTicketUrl($registration->ticket_token)),
        ]);
    }

    public function download(string $token, TicketService $ticketService): Response
    {
        $registration = Registration::query()->where('ticket_token', $token)->firstOrFail();

        return response($ticketService->generatePdf($registration), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="ticket-'.$registration->registration_number.'.pdf"');
    }
}
