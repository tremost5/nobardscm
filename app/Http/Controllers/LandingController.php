<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(EventService $eventService, SettingsService $settingsService): View
    {
        $event = $eventService->currentEvent();
        $posterPath = $settingsService->string('landing_poster_path');

        return view('landing', [
            'brandName' => $settingsService->string('brand_name', config('app.name')),
            'event' => $event,
            'posterPath' => $posterPath,
            'posterUrl' => asset('logo.jpeg'),
            'eventHighlights' => [
                [
                    'label' => 'Tanggal',
                    'value' => $event->date_range_label,
                ],
                [
                    'label' => 'Lokasi',
                    'value' => $event->location ?: 'To be announced',
                ],
                [
                    'label' => 'Status',
                    'value' => $event->status->label(),
                ],
            ],
        ]);
    }
}
