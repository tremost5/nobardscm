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
    <body class="bg-slate-50 font-sans antialiased text-slate-900">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:grid lg:grid-cols-[20rem_minmax(0,1fr)]">
            <x-admin.sidebar :brand-name="config('app.name')" />

            <div class="flex min-h-screen flex-col">
                <x-admin.topbar>
                    {{ $header ?? '' }}
                </x-admin.topbar>

                <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>

                <x-admin.footer />
            </div>
        </div>
    </body>
</html>
