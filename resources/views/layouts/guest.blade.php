<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DSCM Event') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 font-sans antialiased text-slate-100">
        <div class="relative isolate min-h-dvh flex flex-col items-center justify-center overflow-hidden px-4 py-12">
            <div class="absolute inset-0 -z-20 bg-[radial-gradient(circle_at_top_left,_rgba(251,191,36,0.16),_transparent_30%),radial-gradient(circle_at_85%_10%,_rgba(59,130,246,0.14),_transparent_26%),linear-gradient(180deg,_#020617_0%,_#111827_100%)]"></div>
            <div class="absolute inset-0 -z-10 opacity-40 [background-image:linear-gradient(rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.06)_1px,transparent_1px)] [background-size:42px_42px]"></div>

            <div class="w-full max-w-[420px]">
                <div class="mb-8 flex flex-col items-center text-center">
                    <a href="{{ route('landing') }}" class="inline-flex items-center gap-2">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-400 text-sm font-black text-slate-950 shadow-lg shadow-amber-400/30">DS</span>
                        <span class="font-semibold text-white">DSCM Event</span>
                    </a>
                    <h1 class="mt-6 text-2xl font-bold text-white">Login Administrator</h1>
                    <p class="mt-2 text-sm text-slate-400">Masuk untuk mengelola acara.</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-[0_30px_90px_rgba(15,23,42,0.35)] backdrop-blur-xl sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
