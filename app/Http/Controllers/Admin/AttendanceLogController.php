<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceLogController
{
    public function index(Request $request): View
    {
        $query = AuditLog::with(['registration', 'user']);

        if ($request->filled('date')) {
            $query->whereDate('logged_at', $request->date);
        }

        if ($request->filled('panitia')) {
            $query->where('user_id', $request->panitia);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('registration', function ($registrationQuery) use ($search) {
                    $registrationQuery->where('full_name', 'like', "%{$search}%")
                        ->orWhere('registration_number', 'like', "%{$search}%")
                        ->orWhere('church_name', 'like', "%{$search}%");
                })->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('logged_at', 'desc')->paginate(25);
        $panitiaList = User::where('role', 'panitia')->where('status', 'aktif')->get();

        return view('admin.attendance.log', [
            'logs' => $logs,
            'panitiaList' => $panitiaList,
            'filters' => [
                'date' => $request->date,
                'panitia' => $request->panitia,
                'method' => $request->method,
                'event_type' => $request->event_type,
                'search' => $request->search,
            ],
        ]);
    }
}
