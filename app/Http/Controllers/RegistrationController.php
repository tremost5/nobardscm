<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Registration;
use App\Services\EventService;
use App\Services\RegistrationMessageBuilder;
use App\Services\TicketService;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function store(
        StoreRegistrationRequest $request,
        EventService $eventService,
        WhatsAppService $whatsAppService,
        TicketService $ticketService,
        RegistrationMessageBuilder $messageBuilder,
    ): JsonResponse|RedirectResponse {
        $ticketData = $ticketService->generateForRegistration(new Registration());

        $registration = Registration::query()->create([
            'full_name' => (string) $request->string('full_name'),
            'church_name' => (string) $request->string('church_name'),
            'whatsapp_number' => (string) $request->string('whatsapp_number'),
            'bring_snack' => $request->boolean('bring_snack'),
            'registration_number' => $ticketData['registration_number'],
            'ticket_token' => $ticketData['ticket_token'],
        ]);

        try {
            $event = $eventService->currentEvent();
            $message = $messageBuilder->build($registration, $registration->registration_number, $ticketService->generateTicketUrl($registration->ticket_token));
            $whatsAppService->sendRegistrationConfirmation($registration, $event->title, $event->location, $message);
        } catch (\Throwable $exception) {
            Log::warning('Registration succeeded but WhatsApp delivery could not be completed.', [
                'registration_id' => $registration->id,
                'error' => $exception->getMessage(),
            ]);
        }

        if ($request->expectsJson()) {
            session(['registration_id' => $registration->id]);

            return response()->json([
                'message' => 'Pendaftaran berhasil.',
                'registration' => [
                    'id' => $registration->id,
                    'full_name' => $registration->full_name,
                ],
                'ticket_url' => $ticketService->generateTicketUrl($registration->ticket_token),
            ]);
        }

        return redirect()
            ->route('registration.success')
            ->with('registration_id', $registration->id);
    }

    public function success(EventService $eventService): View|RedirectResponse
    {
        $registrationId = session('registration_id');

        if (! $registrationId) {
            return redirect()->route('landing');
        }

        $registration = Registration::query()->find($registrationId);

        if (! $registration) {
            return redirect()->route('landing');
        }

        return view('registration-success', [
            'event' => $eventService->currentEvent(),
            'registration' => $registration,
        ]);
    }
}
