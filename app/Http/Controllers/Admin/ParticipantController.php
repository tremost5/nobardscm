<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\EventService;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParticipantController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $participantsQuery = Registration::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('full_name', 'like', '%'.$search.'%')
                        ->orWhere('church_name', 'like', '%'.$search.'%')
                        ->orWhere('whatsapp_number', 'like', '%'.$search.'%');
                });
            })
            ->latest();

        return view('admin.participants', [
            'participants' => $participantsQuery->paginate(10)->withQueryString(),
            'totalParticipants' => Registration::query()->count(),
            'snackCount' => Registration::query()->where('bring_snack', true)->count(),
            'failedWaCount' => Registration::query()->where('wa_status', 'failed')->count(),
            'search' => $search,
            'breadcrumbs' => [
                ['label' => 'Peserta', 'url' => route('dashboard.participants')],
            ],
        ]);
    }

    public function resendWhatsApp(Registration $registration, EventService $eventService, WhatsAppService $whatsAppService): RedirectResponse
    {
        $event = $eventService->currentEvent();
        $sent = $whatsAppService->resendRegistrationWhatsApp($registration, $event->title, $event->location);

        if ($sent) {
            return redirect()->route('dashboard.participants')->with('status', 'WhatsApp berhasil dikirim ulang.');
        }

        return redirect()->route('dashboard.participants')->with('status', 'WhatsApp gagal dikirim.')->with('wa_error', $registration->wa_error ?? 'Tidak ada detail error.');
    }

    public function resendFailedWhatsApp(EventService $eventService, WhatsAppService $whatsAppService): RedirectResponse
    {
        $event = $eventService->currentEvent();
        $failedRegistrations = Registration::query()->where('wa_status', 'failed')->get();

        foreach ($failedRegistrations as $registration) {
            $whatsAppService->resendRegistrationWhatsApp($registration, $event->title, $event->location);
        }

        return redirect()->route('dashboard.participants')->with('status', 'WhatsApp berhasil dikirim ulang.');
    }
}
