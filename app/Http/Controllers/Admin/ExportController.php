<?php

namespace App\Http\Controllers\Admin;

use App\Models\Registration;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController
{
    public function exportExcel(Request $request): StreamedResponse
    {
        $registrations = Registration::with('attendance.checkedInBy')->get();

        $fileName = 'attendance_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for UTF-8

            // Header row
            fputcsv($file, [
                'Nomor Registrasi',
                'Nama',
                'Gereja',
                'No WhatsApp',
                'Bawa Makanan',
                'Status Kehadiran',
                'Jam Hadir',
                'Metode',
                'Panitia',
            ], ';');

            // Data rows
            foreach ($registrations as $reg) {
                if ($reg->attendance) {
                    $status = 'Sudah Hadir';
                    $time = $reg->attendance->checked_in_at->format('H:i:s');
                    $method = $reg->attendance->checkin_method === 'qr' ? 'QR Scan' : 'Manual';
                    $panitia = $reg->attendance->checkedInBy?->name ?? '-';
                } else {
                    $status = 'Belum Hadir';
                    $time = '-';
                    $method = '-';
                    $panitia = '-';
                }

                fputcsv($file, [
                    $reg->registration_number,
                    $reg->full_name,
                    $reg->church_name,
                    $reg->whatsapp_number,
                    $reg->bring_snack_text,
                    $status,
                    $time,
                    $method,
                    $panitia,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request): StreamedResponse
    {
        // For PDF export, we would typically use a library like mPDF or TCPDF
        // For now, returning a simple message. In production, integrate with PDF library.
        return response()->stream(function () {
            echo "PDF export functionality would be implemented here with mPDF or similar library.";
        }, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
