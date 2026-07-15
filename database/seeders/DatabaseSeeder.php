<?php

namespace Database\Seeders;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@dscmevent.test'],
            [
                'name' => 'DSCM Admin',
                'password' => Hash::make('password'),
            ],
        );

        Event::query()->updateOrCreate(
            ['slug' => 'community-worship-night'],
            [
                'title' => 'Community Worship Night',
                'description' => 'A church-friendly event foundation designed to support future gatherings, celebrations, seminars, and retreats.',
                'location' => 'DSCM Main Hall',
                'start_date' => now()->addMonths(5)->toDateString(),
                'end_date' => now()->addMonths(5)->toDateString(),
                'hero_image' => null,
                'status' => EventStatus::Active,
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'landing_poster_path'],
            ['value' => 'posters/dscm-official-poster.svg'],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'fonnte_token'],
            ['value' => 'demo-fonnte-token'],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'fonnte_number'],
            ['value' => '6281234567890'],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'whatsapp_enabled'],
            ['value' => '1'],
        );
    }
}
