<?php

namespace App\Http\Controllers\Attendance;

use App\Models\Registration;
use App\Models\Attendance;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScanQrController
{
    public function index(): View
    {
        return view('attendance.scan-qr');
    }

    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ticket_token' => 'required|string',
        ]);

        $registration = Registration::where('ticket_token', $validated['ticket_token'])->first();

        if (!$registration) {
            return response()->json(['success' => false, 'message' => 'Tiket tidak ditemukan.'], 404);
        }

        $existingAttendance = Attendance::where('registration_id', $registration->id)->first();

        return response()->json([
            'success' => true,
            'registration' => [
                'id' => $registration->id,
                'full_name' => $registration->full_name,
                'church_name' => $registration->church_name,
                'registration_number' => $registration->registration_number,
                'bring_snack' => $registration->bring_snack_text,
            ],
            'hasAttended' => $existingAttendance ? true : false,
            'attendanceData' => $existingAttendance ? [
                'checked_in_at' => $existingAttendance->checked_in_at->format('Y-m-d H:i:s'),
                'panitia' => $existingAttendance->checkedInBy?->name,
                'method' => $existingAttendance->checkin_method === 'qr' ? 'QR Scan' : 'Manual',
            ] : null,
        ]);
    }

    public function checkin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'method' => 'required|in:qr,manual',
        ]);

        $registration = Registration::find($validated['registration_id']);

        $existingAttendance = Attendance::where('registration_id', $registration->id)->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta sudah hadir',
                'data' => [
                    'name' => $registration->full_name,
                    'panitia' => $existingAttendance->checkedInBy?->name,
                    'time' => $existingAttendance->checked_in_at->format('H:i:s'),
                    'method' => $existingAttendance->checkin_method === 'qr' ? 'QR Scan' : 'Manual',
                ],
            ], 409);
        }

        $attendance = Attendance::create([
            'registration_id' => $registration->id,
            'checked_in_by' => auth()->id(),
            'checked_in_at' => now(),
            'checkin_method' => $validated['method'],
            'ip_address' => $request->ip(),
            'browser' => $request->header('User-Agent'),
            'device' => $request->header('User-Agent'),
        ]);

        AuditLog::create([
            'registration_id' => $registration->id,
            'user_id' => auth()->id(),
            'logged_at' => now(),
            'method' => $validated['method'],
            'event_type' => 'attendance.checkin',
            'action' => 'checkin',
            'description' => 'Check-in melalui '.($validated['method'] === 'qr' ? 'QR' : 'manual'),
            'metadata' => [
                'source' => $validated['method'],
                'registration_number' => $registration->registration_number,
            ],
            'ip_address' => $request->ip(),
            'browser' => $request->header('User-Agent'),
            'device' => $request->header('User-Agent'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil.',
            'data' => [
                'name' => $registration->full_name,
                'time' => $attendance->checked_in_at->format('H:i:s'),
            ],
        ]);
    }
}
