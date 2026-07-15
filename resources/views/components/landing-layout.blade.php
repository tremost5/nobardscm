<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'DSCM Event') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 font-sans text-slate-100 antialiased">
        <div class="relative isolate min-h-dvh overflow-hidden">
            <div class="absolute inset-0 -z-20 bg-[radial-gradient(circle_at_top_left,_rgba(251,191,36,0.18),_transparent_28%),radial-gradient(circle_at_80%_20%,_rgba(16,185,129,0.12),_transparent_24%),linear-gradient(180deg,_#020617_0%,_#0f172a_45%,_#111827_100%)]"></div>
            <div class="absolute inset-x-0 top-0 -z-10 h-[32rem] bg-[linear-gradient(135deg,_rgba(255,255,255,0.12),_transparent_45%)] blur-3xl"></div>
            {{ $slot }}
        </div>
    </body>
</html>
