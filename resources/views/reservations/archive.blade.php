<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Booking operations</p>
                <h2 class="text-2xl font-semibold text-slate-950">Archived Reservations</h2>
            </div>
            <a href="{{ route('station.reservations') }}" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Back to Station</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="font-semibold text-slate-950">Previous Bookings</h3>
                <p class="text-sm text-slate-500">Review past reservations with the passenger full name and email.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Passenger</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Booked</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Trip</th>
                            <th class="px-5 py-3">Price</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Ticket</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($reservations as $reservation)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-950">{{ $reservation->full_name ?: $reservation->user?->name ?: 'Passenger' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $reservation->user?->email ?? 'No email' }}
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $reservation->created_at?->format('h:i A') ?? '—' }}
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $reservation->created_at?->format('M d, Y') ?? '—' }}
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <p>{{ $reservation->schedule?->route?->origin ?? 'Origin' }} to {{ $reservation->schedule?->route?->destination ?? 'Destination' }}</p>
                                    <p class="text-xs text-slate-500">{{ $reservation->schedule?->train?->train_name ?? 'Train' }}</p>
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-950">PHP {{ number_format($reservation->schedule?->fare ?? 0, 2) }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex min-w-[160px] items-center justify-center rounded-full px-3 py-1 text-xs font-semibold {{ $reservation->ticket_status === 'used' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $reservation->ticket_status === 'used' ? 'Used' : 'Not yet used' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex min-w-[160px] items-center justify-center rounded-full border border-slate-200 bg-slate-950 px-4 py-2 text-xs font-semibold text-white">
                                        {{ $reservation->qr_code ?? 'No ticket' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-slate-500">No archived reservations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">{{ $reservations->links() }}</div>
        </div>
    </div>
</x-app-layout>
