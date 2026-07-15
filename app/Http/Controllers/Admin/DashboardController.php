<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\SettingsService;
use App\Services\EventService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(EventService $eventService, SettingsService $settingsService): View|RedirectResponse
    {
        if (auth()->check() && auth()->user()->isPanitia()) {
            return redirect()->route('attendance.dashboard');
        }

        $event = $eventService->activeEvent();

        return view('admin.dashboard', [
            'event' => $event,
            'totalParticipants' => Registration::query()->count(),
            'snackCount' => Registration::query()->where('bring_snack', true)->count(),
            'whatsappEnabled' => $settingsService->boolean('whatsapp_enabled', true),
            'posterPath' => $settingsService->string('landing_poster_path'),
            'latestParticipants' => Registration::query()->latest()->limit(5)->get(),
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
            ],
        ]);
    }
}
