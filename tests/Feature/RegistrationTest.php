<?php

namespace Tests\Feature;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_whatsapp_numbers_can_be_saved(): void
    {
        Registration::query()->create([
            'full_name' => 'First Participant',
            'church_name' => 'Test Church',
            'whatsapp_number' => '6281234567890',
            'bring_snack' => false,
            'registration_number' => 'NBR2026-00001',
            'ticket_token' => 'token-1',
        ]);

        $secondRegistration = Registration::query()->create([
            'full_name' => 'Second Participant',
            'church_name' => 'Test Church',
            'whatsapp_number' => '6281234567890',
            'bring_snack' => true,
            'registration_number' => 'NBR2026-00002',
            'ticket_token' => 'token-2',
        ]);

        $this->assertNotNull($secondRegistration->id);
        $this->assertSame('6281234567890', $secondRegistration->whatsapp_number);
        $this->assertEquals(2, Registration::query()->where('whatsapp_number', '6281234567890')->count());
    }
}
