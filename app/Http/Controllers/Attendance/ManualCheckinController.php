<?php

namespace App\Http\Controllers\Attendance;

use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManualCheckinController
{
    public function index(): View
    {
        return view('attendance.manual-checkin');
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $registrations = Registration::where(function ($q) use ($query) {
            $q->where('full_name', 'like', "%{$query}%")
                ->orWhere('whatsapp_number', 'like', "%{$query}%")
                ->orWhere('registration_number', 'like', "%{$query}%")
                ->orWhere('church_name', 'like', "%{$query}%");
        })
        ->with('attendance')
        ->take(10)
        ->get();

        $results = $registrations->map(function ($reg) {
            $hasAttended = $reg->attendance ? true : false;
            return [
                'id' => $reg->id,
                'full_name' => $reg->full_name,
                'church_name' => $reg->church_name,
                'whatsapp_number' => $reg->whatsapp_number,
                'registration_number' => $reg->registration_number,
                'bring_snack' => $reg->bring_snack_text,
                'hasAttended' => $hasAttended,
                'attendanceData' => $hasAttended ? [
                    'checked_in_at' => $reg->attendance->checked_in_at->format('Y-m-d H:i:s'),
                    'panitia' => $reg->attendance->checkedInBy?->name,
                    'method' => $reg->attendance->checkin_method === 'qr' ? 'QR Scan' : 'Manual',
                ] : null,
            ];
        });

        return response()->json(['results' => $results]);
    }
}
