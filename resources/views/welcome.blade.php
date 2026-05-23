<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Online Ticket Reservation') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <main class="min-h-screen bg-slate-100 text-slate-950">
            <section class="relative min-h-[680px] overflow-hidden bg-slate-950 text-white">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1474487548417-781cb71495f3?auto=format&fit=crop&w=1800&q=80')] bg-cover bg-center opacity-35"></div>
                <div class="absolute inset-0 bg-slate-950/60"></div>

                <nav class="relative z-10 mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                    <a href="/" class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white text-slate-950">
                            <x-application-logo class="h-6 w-6 fill-current" />
                        </span>
                        <span class="font-semibold">TicketRail</span>
                    </a>

                    @if (Route::has('login'))
                        <div class="flex items-center gap-2 text-sm font-semibold sm:gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}" class="rounded-md bg-white px-4 py-2 text-slate-950 transition hover:bg-cyan-100">Dashboard</a>
                            @else
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="hidden rounded-md px-4 py-2 text-slate-100 transition hover:bg-white/10 hover:text-white sm:inline-flex">Create Account</a>
                                @endif
                                <a href="{{ route('login') }}" class="rounded-md border border-white/25 bg-white/10 px-4 py-2 text-white backdrop-blur transition hover:bg-white hover:text-slate-950">Login</a>
                            @endauth
                        </div>
                    @endif
                </nav>

                <div class="relative z-10 mx-auto grid max-w-7xl gap-10 px-4 pb-16 pt-16 sm:px-6 lg:grid-cols-[1fr_0.82fr] lg:px-8 lg:pb-24 lg:pt-24">
                    <div class="flex flex-col justify-center">
                        <p class="text-sm font-semibold uppercase tracking-wide text-cyan-200">Daily train tickets made simple</p>
                        <h1 class="mt-5 max-w-3xl text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">Reserve your seat before you reach the station.</h1>
                        <p class="mt-5 max-w-2xl text-base leading-7 text-slate-200">
                            Browse daily train schedules, choose a route with the stops you need, and keep your reservation ready for travel.
                        </p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            @auth
                                <a href="{{ route('reservations.create') }}" class="inline-flex items-center justify-center rounded-md bg-cyan-300 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-cyan-200">Book a Ticket</a>
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-md border border-white/25 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">Go to Dashboard</a>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md bg-cyan-300 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-cyan-200">Create Customer Account</a>
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-md border border-white/25 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">Sign In to Book</a>
                            @endauth
                        </div>
                    </div>

                    <div class="rounded-lg border border-white/15 bg-white/10 p-4 shadow-2xl shadow-black/30 backdrop-blur">
                        <div class="rounded-md bg-white p-5 text-slate-950">
                            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Available daily</p>
                                    <h2 class="mt-1 text-xl font-bold">Featured Trips</h2>
                                </div>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Open</span>
                            </div>

                            <div class="mt-5 space-y-3">
                                @forelse ($featuredSchedules as $schedule)
                                    <div class="rounded-md border border-slate-200 bg-slate-50 px-4 py-3">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="font-semibold">{{ $schedule->route?->origin ?? 'Origin' }} to {{ $schedule->route?->destination ?? 'Destination' }}</p>
                                                <p class="mt-1 text-sm text-slate-500">{{ $schedule->train?->train_name ?? 'Train' }} - {{ \Illuminate\Support\Carbon::parse($schedule->departure_time)->format('h:i A') }} daily</p>
                                            </div>
                                            <span class="shrink-0 text-sm font-bold text-slate-700">PHP {{ number_format($schedule->fare, 2) }}</span>
                                        </div>

                                        @if ($schedule->route?->stops?->isNotEmpty())
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach ($schedule->route->stops as $stop)
                                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-cyan-700 ring-1 ring-cyan-100">{{ $stop->station_name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="rounded-md border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">
                                        Daily trips will appear here once schedules are added.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Public ticket validation panel (gate screen) -->
            <section class="mx-auto mt-8 max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-xl bg-white/80 px-8 py-10 shadow-lg backdrop-blur-sm">
                    <h2 class="text-center text-2xl font-semibold text-slate-900">Enter Ticket Reference</h2>
                    <p class="mt-2 text-center text-sm text-slate-500">Type your one-time ticket code below and press Enter to validate.</p>

                    @if(session('ticket_validate_status'))
                        @php
                            $isSuccess = session('ticket_validate_status') === 'success';
                            $ticketUsed = session('ticket_used');
                        @endphp
                        <div class="mt-6 rounded-3xl border px-6 py-5 text-left shadow-sm {{ $isSuccess ? 'border-emerald-200 bg-emerald-50 text-emerald-900' : 'border-rose-200 bg-rose-50 text-rose-900' }}">
                            <p class="font-semibold text-sm uppercase tracking-[0.24em]">{{ $isSuccess ? 'Valid ticket' : 'Ticket status' }}</p>
                            <p class="mt-2 text-base font-semibold">{{ session('ticket_validate_message') }}</p>
                            @if(session('ticket_code'))
                                <p class="mt-2 text-sm text-slate-700">Reference: <span class="font-mono">{{ session('ticket_code') }}</span></p>
                            @endif
                            @if($isSuccess)
                                @if(session('ticket_full_name'))
                                    <p class="mt-1 text-sm text-slate-700">Belongs to: {{ session('ticket_full_name') }}</p>
                                @endif
                            @else
                                @if(session('ticket_full_name'))
                                    <p class="mt-1 text-sm text-slate-700">Belongs to: {{ session('ticket_full_name') }}</p>
                                @endif
                                <p class="mt-1 text-sm uppercase tracking-[0.16em] text-slate-700">Status: {{ $ticketUsed ? 'Used' : 'Invalid' }}</p>
                            @endif
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ticket.validate') }}" class="mt-6">
                        @csrf
                        <div class="mx-auto max-w-2xl">
                            <label for="ticket_code" class="sr-only">Ticket reference</label>
                            <input name="ticket_code" id="ticket_code" type="text" maxlength="9" autofocus placeholder="XXXX-XXXX"
                                   class="w-full rounded-xl border border-slate-200 bg-white px-6 py-6 text-center text-4xl font-mono tracking-widest text-slate-900 placeholder:text-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-300" />
                            <x-input-error :messages="$errors->get('ticket_code')" class="mt-2 text-sm text-rose-600" />
                        </div>

                        <div class="mt-6 flex justify-center">
                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-12 py-3 text-sm font-semibold text-white shadow transition hover:bg-black whitespace-nowrap">Validate Ticket</button>
                        </div>
                    </form>
                </div>

                <script>
                    // Auto-format: uppercase and insert dash after 4 chars
                    (function(){
                        var el = document.getElementById('ticket_code');
                        if (!el) return;
                        el.addEventListener('input', function(e){
                            var v = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g,'');
                            if (v.length > 4) v = v.slice(0,4) + '-' + v.slice(4,8);
                            e.target.value = v;
                        });
                    })();
                </script>
            </section>

            <section class="mx-auto grid max-w-7xl gap-4 px-4 py-8 sm:grid-cols-3 sm:px-6 lg:px-8">
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-cyan-700">1. Pick a route</p>
                    <h3 class="mt-2 font-bold text-slate-950">Find the stops that fit your trip.</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">See direct routes or routes with intermediate stops before booking.</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-cyan-700">2. Reserve a seat</p>
                    <h3 class="mt-2 font-bold text-slate-950">Choose your train and seat number.</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Available seats update as reservations are made.</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-cyan-700">3. Travel daily</p>
                    <h3 class="mt-2 font-bold text-slate-950">Schedules repeat every day.</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Just check the departure time and arrive ready.</p>
                </div>
            </section>
        </main>
    </body>
</html>
