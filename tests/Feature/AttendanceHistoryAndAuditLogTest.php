<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AttendanceHistoryAndAuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_panitia_history_shows_only_current_panitia_attendance_records(): void
    {
        $panitia = User::factory()->create([
            'role' => 'panitia',
            'status' => 'aktif',
        ]);
        $otherPanitia = User::factory()->create([
            'role' => 'panitia',
            'status' => 'aktif',
        ]);

        $currentRegistration = Registration::create([
            'full_name' => 'Dian Pratama',
            'church_name' => 'Gereja Test',
            'whatsapp_number' => '628111111111',
            'bring_snack' => true,
            'registration_number' => 'REG-001',
            'ticket_token' => (string) Str::uuid(),
        ]);

        $otherRegistration = Registration::create([
            'full_name' => 'Rina Putri',
            'church_name' => 'Gereja Lain',
            'whatsapp_number' => '628222222222',
            'bring_snack' => false,
            'registration_number' => 'REG-002',
            'ticket_token' => (string) Str::uuid(),
        ]);

        Attendance::create([
            'registration_id' => $currentRegistration->id,
            'checked_in_by' => $panitia->id,
            'checked_in_at' => now(),
            'checkin_method' => 'qr',
        ]);

        Attendance::create([
            'registration_id' => $otherRegistration->id,
            'checked_in_by' => $otherPanitia->id,
            'checked_in_at' => now(),
            'checkin_method' => 'manual',
        ]);

        $this->actingAs($panitia)
            ->get('/attendance/history')
            ->assertOk()
            ->assertSee('Riwayat Saya')
            ->assertSee('Dian Pratama')
            ->assertDontSee('Rina Putri');
    }

    public function test_superadmin_can_view_audit_log_records(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'status' => 'aktif',
        ]);
        $panitia = User::factory()->create([
            'role' => 'panitia',
            'status' => 'aktif',
        ]);

        $registration = Registration::create([
            'full_name' => 'Budi Santoso',
            'church_name' => 'Gereja Baru',
            'whatsapp_number' => '628333333333',
            'bring_snack' => false,
            'registration_number' => 'REG-003',
            'ticket_token' => (string) Str::uuid(),
        ]);

        AuditLog::create([
            'registration_id' => $registration->id,
            'user_id' => $panitia->id,
            'logged_at' => now(),
            'method' => 'qr',
            'event_type' => 'attendance.checkin',
            'action' => 'checkin',
            'description' => 'Check-in melalui QR',
            'metadata' => ['source' => 'qr'],
        ]);

        $this->actingAs($superadmin)
            ->get('/admin/attendance-log')
            ->assertOk()
            ->assertSee('Riwayat Kehadiran')
            ->assertSee('REG-003')
            ->assertSee('QR Scan');
    }
}
