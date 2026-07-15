<?php

namespace App\Http\Controllers\Admin;

use App\Models\Registration;
use App\Models\User;
use Illuminate\View\View;

class AttendanceDashboardController
{
    public function index(): View
    {
        $totalParticipants = Registration::count();
        $presentCount = Registration::whereHas('attendance')->distinct()->count();
        $absentCount = $totalParticipants - $presentCount;
        $qrCount = Registration::whereHas('attendance', function ($query) {
            $query->where('checkin_method', 'qr');
        })->distinct()->count();
        $manualCount = Registration::whereHas('attendance', function ($query) {
            $query->where('checkin_method', 'manual');
        })->distinct()->count();
        $activePanitia = User::where('role', 'panitia')->where('status', 'aktif')->count();
        $attendancePercentage = $totalParticipants > 0 ? round(($presentCount / $totalParticipants) * 100, 2) : 0;

        return view('admin.attendance.dashboard', [
            'totalParticipants' => $totalParticipants,
            'presentCount' => $presentCount,
            'absentCount' => $absentCount,
            'qrCount' => $qrCount,
            'manualCount' => $manualCount,
            'activePanitia' => $activePanitia,
            'attendancePercentage' => $attendancePercentage,
        ]);
    }
}
