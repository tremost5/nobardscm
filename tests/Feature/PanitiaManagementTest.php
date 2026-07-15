<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PanitiaManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_create_panitia_without_manual_email_or_password_fields(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($superadmin)->post(route('admin.panitia.store'), [
            'name' => 'Lukas Sitorus',
            'phone' => '081234567890',
        ]);

        $response->assertRedirect(route('admin.panitia.index'));

        $panitia = User::where('role', 'panitia')->where('name', 'Lukas Sitorus')->firstOrFail();

        $this->assertNotNull($panitia->username);
        $this->assertSame('081234567890', $panitia->phone);
        $this->assertTrue($panitia->force_password_change);
        $this->assertNotEmpty($panitia->password);
    }

    public function test_superadmin_can_reset_panitia_password_and_keep_it_private(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'status' => 'aktif',
        ]);

        $panitia = User::factory()->create([
            'role' => 'panitia',
            'status' => 'aktif',
            'force_password_change' => false,
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($superadmin)->post(route('admin.panitia.reset-password', $panitia));

        $response->assertRedirect(route('admin.panitia.index'));

        $panitia->refresh();

        $this->assertTrue($panitia->force_password_change);
        $this->assertNotSame('old-password', $panitia->password);
        $this->assertNotNull($panitia->password_sent_at);
    }
}
