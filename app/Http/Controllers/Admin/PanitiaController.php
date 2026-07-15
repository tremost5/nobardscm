<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PanitiaController
{
    public function __construct(private readonly WhatsAppService $whatsAppService)
    {
    }

    public function index(): View
    {
        $panitiaList = User::where('role', 'panitia')->latest()->paginate(15);

        return view('admin.panitia.index', ['panitiaList' => $panitiaList]);
    }

    public function create(): View
    {
        return view('admin.panitia.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $validated['status'] = 'aktif';

        $username = $this->generateUniqueUsername($validated['name']);
        $temporaryPassword = $this->generateTemporaryPassword();
        $email = $this->generateUniqueEmail($username);

        $panitia = User::create([
            'name' => $validated['name'],
            'email' => $email,
            'username' => $username,
            'phone' => $validated['phone'],
            'password' => Hash::make($temporaryPassword),
            'role' => 'panitia',
            'status' => 'aktif',
            'force_password_change' => true,
            'password_sent_at' => now(),
        ]);

        $this->sendCredentials($panitia, $temporaryPassword);

        return redirect()->route('admin.panitia.index')->with('success', 'Panitia berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        if ($user->role !== 'panitia') {
            abort(404);
        }

        return view('admin.panitia.edit', ['panitia' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'panitia') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.panitia.index')->with('success', 'Panitia berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role !== 'panitia') {
            abort(404);
        }

        $user->delete();

        return redirect()->route('admin.panitia.index')->with('success', 'Panitia berhasil dihapus.');
    }

    public function resetPassword(User $panitia): RedirectResponse
    {
        if ($panitia->role !== 'panitia') {
            abort(404);
        }

        $temporaryPassword = $this->generateTemporaryPassword();
        $panitia->force_password_change = true;
        $panitia->password = Hash::make($temporaryPassword);
        $panitia->password_sent_at = now();
        $panitia->save();

        $this->sendCredentials($panitia, $temporaryPassword);

        return redirect()->route('admin.panitia.index')->with('success', 'Password panitia berhasil direset dan dikirim melalui WhatsApp.');
    }

    private function generateUniqueUsername(string $name): string
    {
        $base = Str::of(trim($name))
            ->explode(' ')
            ->filter()
            ->first();

        $base = Str::slug(Str::lower((string) $base)) ?: 'panitia';
        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $counter++;
            $username = $base.$counter;
        }

        return $username;
    }

    private function generateUniqueEmail(string $username): string
    {
        $email = $username.'@dscmevent.test';
        $counter = 2;

        while (User::where('email', $email)->exists()) {
            $email = $username.$counter.'@dscmevent.test';
            $counter++;
        }

        return $email;
    }

    private function generateTemporaryPassword(): string
    {
        return Str::random(10);
    }

    private function sendCredentials(User $panitia, string $temporaryPassword): void
    {
        $loginUrl = route('login', absolute: true);
        $this->whatsAppService->sendPanitiaCredentials($panitia, $temporaryPassword, $loginUrl);
    }
}
