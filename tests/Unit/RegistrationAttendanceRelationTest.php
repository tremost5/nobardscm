<?php

namespace Tests\Unit;

use App\Models\Registration;
use Tests\TestCase;

class RegistrationAttendanceRelationTest extends TestCase
{
    public function test_registration_has_attendance_relationship_method(): void
    {
        $registration = new Registration();

        $this->assertTrue(method_exists($registration, 'attendance'));
    }
}
