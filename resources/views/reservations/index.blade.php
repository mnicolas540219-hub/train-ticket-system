<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Booking operations</p>
                <h2 class="text-2xl font-semibold text-slate-950">Reservations</h2>
            </div>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('reservations.archive') }}" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Archive</a>
            @else
                <a href="{{ route('reservations.create') }}" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">New Reservation</a>
            @endif
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
        @endif
        @if (session('success'))
            <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-5 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">{{ session('error') }}</div>
        @endif

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="font-semibold text-slate-950">Passenger Bookings</h3>
                    <p class="text-sm text-slate-500">Review reservations. Customers pay in cash at the station, so only mark payment as paid once they have paid in person.</p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Passenger</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Trip</th>
                            <th class="px-5 py-3">Status</th>
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
                                    <p>{{ $reservation->schedule?->route?->origin ?? 'Origin' }} to {{ $reservation->schedule?->route?->destination ?? 'Destination' }}</p>
                                    <p class="text-xs text-slate-500">{{ $reservation->schedule?->train?->train_name ?? 'Train' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $reservation->payment_status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' }}">
                                        {{ $reservation->payment_status === 'pending' ? 'Issue requested' : 'Paid' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-slate-500">No reservations yet. Create a booking from an available schedule.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">{{ $reservations->links() }}</div>
        </div>
    </div>
</x-app-layout>
