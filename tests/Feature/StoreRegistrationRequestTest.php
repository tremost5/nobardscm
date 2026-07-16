<?php

namespace Tests\Feature;

use App\Http\Requests\StoreRegistrationRequest;
use Illuminate\Validation\Rules\Unique;
use Tests\TestCase;

class StoreRegistrationRequestTest extends TestCase
{
    public function test_registration_request_allows_duplicate_whatsapp_numbers(): void
    {
        $request = new StoreRegistrationRequest();
        $rules = $request->rules();
        $whatsappRules = $rules['whatsapp_number'] ?? [];

        $this->assertContains('required', $whatsappRules);
        $this->assertContains('string', $whatsappRules);
        $this->assertContains('regex:/^62\d{8,13}$/', $whatsappRules);
        $this->assertTrue(
            collect($whatsappRules)->every(fn ($rule) => ! $rule instanceof Unique),
            'WhatsApp number validation should not enforce uniqueness.'
        );
    }
}
