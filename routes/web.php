<?php

use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WhatsAppSettingController;
use App\Http\Controllers\Admin\AttendanceDashboardController;
use App\Http\Controllers\Admin\PanitiaController;
use App\Http\Controllers\Admin\AttendanceLogController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Attendance\HistoryController;
use App\Http\Controllers\Attendance\ScanQrController;
use App\Http\Controllers\Attendance\ManualCheckinController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Panitia\PasswordChangeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\ThrottleRegistrationSubmission;
use App\Http\Middleware\EnsureSuperadmin;
use App\Http\Middleware\EnsurePanitia;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');
Route::post('/registrations', [RegistrationController::class, 'store'])->middleware(ThrottleRegistrationSubmission::class)->name('registrations.store');
Route::get('/registration/success', [RegistrationController::class, 'success'])->name('registration.success');
Route::get('/ticket/{token}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/ticket/{token}/download', [TicketController::class, 'download'])->name('ticket.download');

Route::redirect('/login', '/admin/login');
Route::redirect('/admin/dashboard', '/dashboard');
Route::redirect('/admin', '/dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    
    // Superadmin routes
    Route::middleware(EnsureSuperadmin::class)->group(function () {
        Route::get('/dashboard/participants', ParticipantController::class)->name('dashboard.participants');
        Route::get('/dashboard/whatsapp-settings', [WhatsAppSettingController::class, 'index'])->name('dashboard.whatsapp-settings');
        Route::post('/dashboard/whatsapp-settings', [WhatsAppSettingController::class, 'update'])->name('dashboard.whatsapp-settings.update');
        Route::post('/dashboard/whatsapp-settings/test', [WhatsAppSettingController::class, 'testConnection'])->name('dashboard.whatsapp-settings.test');
        
        // Attendance Dashboard
        Route::get('/dashboard/attendance', [AttendanceDashboardController::class, 'index'])->name('admin.attendance.dashboard');
        
        // Panitia Management
        Route::post('/admin/panitia/{panitia}/reset-password', [PanitiaController::class, 'resetPassword'])->name('admin.panitia.reset-password');
        Route::resource('admin/panitia', PanitiaController::class, [
            'names' => [
                'index' => 'admin.panitia.index',
                'create' => 'admin.panitia.create',
                'store' => 'admin.panitia.store',
                'edit' => 'admin.panitia.edit',
                'update' => 'admin.panitia.update',
                'destroy' => 'admin.panitia.destroy',
            ]
        ]);
        
        // Attendance Log
        Route::get('/admin/attendance-log', [AttendanceLogController::class, 'index'])->name('admin.attendance.log');
        
        // Export
        Route::get('/admin/export/excel', [ExportController::class, 'exportExcel'])->name('admin.export.excel');
        Route::get('/admin/export/pdf', [ExportController::class, 'exportPdf'])->name('admin.export.pdf');
    });
    
    // Panitia routes
    Route::middleware(EnsurePanitia::class)->group(function () {
        Route::get('/panitia/password/change', [PasswordChangeController::class, 'show'])->name('panitia.password.change');
        Route::post('/panitia/password/change', [PasswordChangeController::class, 'update'])->name('panitia.password.change.update');

        Route::get('/attendance/dashboard', function () {
            return view('attendance.dashboard');
        })->name('attendance.dashboard');

        Route::get('/attendance/scan-qr', [ScanQrController::class, 'index'])->name('attendance.scan-qr');
        Route::post('/attendance/scan-qr/verify', [ScanQrController::class, 'verify'])->name('attendance.scan-qr.verify');
        Route::post('/attendance/scan-qr/checkin', [ScanQrController::class, 'checkin'])->name('attendance.scan-qr.checkin');
        
        Route::get('/attendance/manual-checkin', [ManualCheckinController::class, 'index'])->name('attendance.manual-checkin');
        Route::get('/attendance/manual-checkin/search', [ManualCheckinController::class, 'search'])->name('attendance.manual-checkin.search');

        Route::get('/attendance/history', [HistoryController::class, 'index'])->name('attendance.history');

        Route::get('/attendance/profile', function () {
            return view('attendance.profile');
        })->name('attendance.profile');
    });
});

require __DIR__.'/auth.php';
