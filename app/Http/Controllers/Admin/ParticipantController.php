<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParticipantController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $participantsQuery = Registration::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('full_name', 'like', '%'.$search.'%')
                        ->orWhere('church_name', 'like', '%'.$search.'%')
                        ->orWhere('whatsapp_number', 'like', '%'.$search.'%');
                });
            })
            ->latest();

        return view('admin.participants', [
            'participants' => $participantsQuery->paginate(10)->withQueryString(),
            'totalParticipants' => Registration::query()->count(),
            'snackCount' => Registration::query()->where('bring_snack', true)->count(),
            'search' => $search,
            'breadcrumbs' => [
                ['label' => 'Peserta', 'url' => route('dashboard.participants')],
            ],
        ]);
    }
}
