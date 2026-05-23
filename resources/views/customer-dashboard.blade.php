<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Customer dashboard</p>
                <h2 class="text-2xl font-semibold leading-tight text-slate-950">
                    {{ __('My Travel') }}
                </h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('reservations.create') }}" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    {{ __('Book a ticket') }}
                </a>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-slate-50">
                    {{ __('Edit profile') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-slate-100">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid gap-4 lg:grid-cols-3">
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Ready to book</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-950">{{ number_format($dashboardStats['available_trips']) }}</p>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Upcoming schedules with open seats.</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Your bookings</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-950">{{ number_format($dashboardStats['my_bookings']) }}</p>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Tickets reserved under your account.</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Profile access</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-950">{{ Auth::user()->name }}</p>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Update your email or password anytime.</p>
                </div>
            </div>

                    @if(session('success'))
                        <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mt-6 rounded-lg border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-700">{{ session('error') }}</div>
                    @endif

            <section class="mt-6 grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <div>
                            <h3 class="font-semibold text-slate-950">Available trips</h3>
                            <p class="text-sm text-slate-500">Select a schedule to reserve your seat.</p>
                        </div>
                        <a href="{{ route('reservations.create') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-950">Book now</a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($availableSchedules as $schedule)
                            <div class="grid gap-4 px-5 py-4 sm:grid-cols-[1fr_auto] sm:items-center">
                                <div>
                                    <p class="font-semibold text-slate-950">{{ $schedule->route?->origin ?? 'Origin' }} to {{ $schedule->route?->destination ?? 'Destination' }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $schedule->train?->train_name ?? 'Train' }} · {{ \Illuminate\Support\Carbon::parse($schedule->departure_time)->format('h:i A') }}</p>
                                </div>
                                <div class="flex flex-col items-start gap-2 sm:items-end">
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ number_format($schedule->available_seats) }} seats</span>
                                    <a href="{{ route('reservations.create') }}" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-800">Reserve</a>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-sm text-slate-500">No available trips found at the moment.</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="font-semibold text-slate-950">My tickets</h3>
                        <p class="text-sm text-slate-500">Your last reservations.</p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($myReservations as $reservation)
                            <div class="px-5 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-950">Seat {{ $reservation->seat_number ?? 'Unassigned' }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $reservation->schedule?->route?->origin ?? 'Origin' }} to {{ $reservation->schedule?->route?->destination ?? 'Destination' }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $reservation->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $reservation->payment_status === 'paid' ? 'Paid' : 'Pending payment' }}
                                    </span>
                                </div>
                                <p class="mt-3 text-xs uppercase tracking-wide text-slate-400">{{ $reservation->created_at?->format('M d, Y h:i A') }}</p>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-sm text-slate-500">You haven't booked any tickets yet.</div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="mt-6">
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="font-semibold text-slate-950">Submit ticket</h3>
                        <p class="text-sm text-slate-500">Type the ticket reference you received and submit it to validate your ride.</p>
                    </div>

                    <div class="px-5 py-6">
                        @if(session('ticket_submit_status'))
                            @php
                                $isSuccess = session('ticket_submit_status') === 'success';
                            @endphp
                            <div class="mb-4 rounded-2xl border px-4 py-4 text-sm {{ $isSuccess ? 'border-emerald-200 bg-emerald-50 text-emerald-900' : 'border-rose-200 bg-rose-50 text-rose-900' }}">
                                <p class="font-semibold">{{ session('ticket_submit_message') }}</p>
                                <p class="mt-2">Reference: <span class="font-mono">{{ session('ticket_code') }}</span></p>
                                @if(session('ticket_owner'))
                                    <p class="mt-1">Belongs to: {{ session('ticket_owner') }}</p>
                                @endif
                                @if(session('ticket_used'))
                                    <p class="mt-1 text-xs uppercase tracking-wide">Status: Used</p>
                                @endif
                            </div>
                        @endif

                        <form method="POST" action="{{ route('dashboard.ticket.submit') }}" class="grid gap-4">
                            @csrf

                            <div>
                                <label for="ticket_code" class="block text-sm font-medium text-slate-700">Ticket reference</label>
                                <input id="ticket_code" name="ticket_code" type="text" value="{{ old('ticket_code') }}" placeholder="XXXX-XXXX"
                                       class="mt-2 block w-full rounded-md border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-slate-700 focus:ring-slate-700" />
                                <x-input-error :messages="$errors->get('ticket_code')" class="mt-2" />
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Submit ticket</button>
                                <p class="text-sm text-slate-500">If the ticket is valid and unused, you will receive “Safe travels”.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
