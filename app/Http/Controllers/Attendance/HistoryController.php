<?php

namespace App\Http\Controllers\Attendance;

use App\Models\Attendance;
use Illuminate\View\View;

class HistoryController
{
    public function index(): View
    {
        $entries = Attendance::query()
            ->where('checked_in_by', auth()->id())
            ->with(['registration', 'checkedInBy'])
            ->latest('checked_in_at')
            ->paginate(15)
            ->withQueryString();

        return view('attendance.history', [
            'entries' => $entries,
        ]);
    }
}
