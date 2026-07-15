<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Ticket</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; background: #020617; color: #f8fafc; padding: 24px; }
        .card { border: 1px solid #fbbf24; border-radius: 20px; padding: 24px; background: #111827; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 12px; }
        .meta { margin-bottom: 8px; color: #cbd5e1; }
        .poster { width: 100%; height: auto; margin-bottom: 18px; border-radius: 16px; }
        .qrcode { margin-top: 18px; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ public_path('logo.jpeg') }}" class="poster" alt="Poster">
        <div class="title">{{ $registration->registration_number }}</div>
        <div class="meta"><strong>Nama:</strong> {{ $registration->full_name }}</div>
        <div class="meta"><strong>Gereja:</strong> {{ $registration->church_name }}</div>
        <div class="meta"><strong>WhatsApp:</strong> {{ $registration->whatsapp_number }}</div>
        <div class="meta"><strong>Bawa Snack:</strong> {{ $registration->bring_snack_text }}</div>
        <div class="qrcode">{!! $qrCode !!}</div>
    </div>
</body>
</html>
