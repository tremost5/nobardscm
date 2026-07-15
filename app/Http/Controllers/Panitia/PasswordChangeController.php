<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordChangeController extends Controller
{
    public function show(): View
    {
        return view('panitia.password-change');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        $user->password = Hash::make($request->input('password'));
        $user->force_password_change = false;
        $user->save();

        return redirect()->route('attendance.dashboard')->with('success', 'Password berhasil diperbarui. Silakan gunakan password baru Anda mulai sekarang. Tuhan Yesus Memberkati.');
    }
}
