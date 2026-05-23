<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Rail operations</p>
                <h2 class="text-2xl font-semibold leading-tight text-slate-950">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <a href="{{ route('reservations.index') }}" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                View Reservations
            </a>
        </div>
    </x-slot>

    @php
        $statCards = [
            ['label' => 'Total Trains', 'value' => $stats['trains'], 'detail' => 'Fleet records ready for scheduling', 'tone' => 'border-sky-200 bg-sky-50 text-sky-700'],
            ['label' => 'Active Routes', 'value' => $stats['routes'], 'detail' => 'Origins and destinations configured', 'tone' => 'border-emerald-200 bg-emerald-50 text-emerald-700'],
            ['label' => 'Daily Trips', 'value' => $stats['upcoming_schedules'], 'detail' => 'Recurring departures configured', 'tone' => 'border-amber-200 bg-amber-50 text-amber-700'],
            ['label' => 'Reservations', 'value' => $stats['reservations'], 'detail' => 'Passenger bookings captured', 'tone' => 'border-rose-200 bg-rose-50 text-rose-700'],
        ];
    @endphp

    <div class="bg-slate-100">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <section class="grid gap-4 lg:grid-cols-[1.35fr_0.65fr]">
                <div class="overflow-hidden rounded-lg bg-slate-950 p-6 text-white shadow-sm">
                    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                        <div class="max-w-2xl">
                            <p class="text-sm font-medium text-cyan-200">Good day, {{ Auth::user()->name }}</p>
                            <h3 class="mt-3 text-3xl font-semibold tracking-normal sm:text-4xl">Keep bookings, seats, and departures moving.</h3>
                            <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">
                                Monitor today&apos;s rail activity, scan booking status, and jump into the main management areas from one focused workspace.
                            </p>
                        </div>
                        <div class="grid min-w-48 grid-cols-2 gap-3 rounded-lg border border-white/10 bg-white/5 p-4">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Open Seats</p>
                                <p class="mt-1 text-2xl font-semibold">{{ number_format($stats['available_seats']) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Paid Tickets</p>
                                <p class="mt-1 text-2xl font-semibold">{{ number_format($stats['paid_tickets']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-semibold text-slate-950">Quick Actions</p>
                    <div class="mt-4 grid gap-3">
                        <a href="{{ route('schedules.create') }}" class="flex items-center justify-between rounded-md border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                            Add Schedule
                            <span aria-hidden="true">+</span>
                        </a>
                        <a href="{{ route('trains.create') }}" class="flex items-center justify-between rounded-md border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                            Add Train
                            <span aria-hidden="true">+</span>
                        </a>
                        <a href="{{ route('routes.create') }}" class="flex items-center justify-between rounded-md border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                            Add Route
                            <span aria-hidden="true">+</span>
                        </a>
                    </div>
                </div>
            </section>

            <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($statCards as $card)
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-medium text-slate-500">{{ $card['label'] }}</p>
                            <span class="h-2.5 w-2.5 rounded-full border {{ $card['tone'] }}"></span>
                        </div>
                        <p class="mt-4 text-3xl font-semibold text-slate-950">{{ number_format($card['value']) }}</p>
                        <p class="mt-2 text-sm leading-5 text-slate-500">{{ $card['detail'] }}</p>
                    </div>
                @endforeach
            </section>

            <section class="mt-6 grid gap-6 lg:grid-cols-[1fr_0.9fr]">
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <div>
                            <h3 class="font-semibold text-slate-950">Daily Departures</h3>
                            <p class="text-sm text-slate-500">Recurring schedules passengers can book.</p>
                        </div>
                        <a href="{{ route('schedules.index') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-950">Manage</a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($nextSchedules as $schedule)
                            <div class="grid gap-4 px-5 py-4 sm:grid-cols-[1fr_auto] sm:items-center">
                                <div>
                                    <p class="font-semibold text-slate-950">
                                        {{ $schedule->route?->origin ?? 'Origin' }} to {{ $schedule->route?->destination ?? 'Destination' }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $schedule->train?->train_name ?? 'Unassigned train' }} &middot; {{ \Illuminate\Support\Carbon::parse($schedule->departure_time)->format('h:i A') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-3 sm:justify-end">
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        {{ number_format($schedule->available_seats) }} seats
                                    </span>
                                    <span class="text-sm font-semibold text-slate-950">
                                        PHP {{ number_format($schedule->fare, 2) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-sm text-slate-500">
                                No daily schedules yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <div>
                            <h3 class="font-semibold text-slate-950">Recent Reservations</h3>
                            <p class="text-sm text-slate-500">Latest passenger booking activity.</p>
                        </div>
                        <a href="{{ route('reservations.index') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-950">Open</a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentReservations as $reservation)
                            <div class="px-5 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-950">{{ $reservation->user?->name ?? 'Passenger' }}</p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            Seat {{ $reservation->seat_number }} &middot; {{ $reservation->schedule?->route?->origin ?? 'Origin' }} to {{ $reservation->schedule?->route?->destination ?? 'Destination' }}
                                        </p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $reservation->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ ucfirst($reservation->payment_status) }}
                                    </span>
                                </div>
                                <p class="mt-3 text-xs font-medium uppercase tracking-wide text-slate-400">
                                    {{ $reservation->created_at?->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-sm text-slate-500">
                                No reservations recorded yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
