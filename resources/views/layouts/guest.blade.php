<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center bg-slate-950 px-4 py-8">
            <div class="mb-6 text-center">
                <a href="/" class="inline-flex h-14 w-14 items-center justify-center rounded-lg bg-white text-slate-950 shadow-sm">
                    <x-application-logo class="h-9 w-9 fill-current" />
                </a>
                <p class="mt-4 text-sm font-medium text-cyan-200">Online Ticket Reservation</p>
            </div>

            <div class="w-full max-w-md overflow-hidden rounded-lg border border-white/10 bg-white p-6 shadow-2xl shadow-black/30">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
