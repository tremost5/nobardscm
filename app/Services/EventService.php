<?php

namespace App\Services;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\Setting;

class EventService
{
    public function currentEvent(): Event
    {
        return Event::query()->active()->orderBy('start_date')->first()
            ?? Event::query()->orderByDesc('start_date')->first()
            ?? new Event([
                'title' => 'Community Worship Night',
                'slug' => 'community-worship-night',
                'description' => 'A church-friendly event foundation designed to support future gatherings, celebrations, seminars, and retreats.',
                'location' => 'DSCM Main Hall',
                'start_date' => now()->addMonths(5),
                'end_date' => now()->addMonths(5),
                'hero_image' => null,
                'status' => EventStatus::Active,
            ]);
    }

    public function activeEvent(): ?Event
    {
        return Event::query()->active()->orderBy('start_date')->first();
    }

    public function landingPageData(): array
    {
        $event = $this->currentEvent();

        return [
            'brandName' => Setting::getValue('brand_name', config('app.name')),
            'event' => $event,
            'highlights' => [
                [
                    'label' => 'Event date',
                    'value' => $event->date_range_label,
                ],
                [
                    'label' => 'Location',
                    'value' => $event->location ?: 'To be announced',
                ],
                [
                    'label' => 'Status',
                    'value' => $event->status instanceof EventStatus ? $event->status->label() : ucfirst((string) $event->status),
                ],
            ],
            'aboutPoints' => [
                'Reusable event foundation for church, community, and seasonal programs.',
                'Configurable content structure so future events can launch without code duplication.',
                'A clean UI system that keeps landing pages and admin screens visually aligned.',
            ],
            'gallery' => [
                [
                    'title' => 'Welcome moments',
                    'image' => 'https://placehold.co/900x700/0f172a/f8fafc?text=Gallery+01',
                ],
                [
                    'title' => 'Prayer and reflection',
                    'image' => 'https://placehold.co/900x700/172554/e2e8f0?text=Gallery+02',
                ],
                [
                    'title' => 'Community time',
                    'image' => 'https://placehold.co/900x700/713f12/fef3c7?text=Gallery+03',
                ],
                [
                    'title' => 'Worship atmosphere',
                    'image' => 'https://placehold.co/900x700/14532d/d1fae5?text=Gallery+04',
                ],
            ],
            'countdown' => [
                ['value' => '00', 'label' => 'Days'],
                ['value' => '00', 'label' => 'Hours'],
                ['value' => '00', 'label' => 'Minutes'],
                ['value' => '00', 'label' => 'Seconds'],
            ],
        ];
    }
}
