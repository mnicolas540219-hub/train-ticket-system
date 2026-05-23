<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Trip operations</p>
                <h2 class="text-2xl font-semibold text-slate-950">Schedules</h2>
            </div>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('schedules.create') }}" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Add Schedule</a>
            @endif
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
        @endif

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="font-semibold text-slate-950">Departure Board</h3>
                <p class="text-sm text-slate-500">
                    @if (Auth::user()->isAdmin())
                        Manage train assignments, fares, seats, and daily departure times.
                    @else
                        Browse available schedules for booking.
                    @endif
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Route</th>
                            <th class="px-5 py-3">Train</th>
                            <th class="px-5 py-3">Departure</th>
                            <th class="px-5 py-3">Fare</th>
                            <th class="px-5 py-3">Seats</th>
                            @if (Auth::user()->isAdmin())
                                <th class="px-5 py-3 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($schedules as $schedule)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4 font-semibold text-slate-950">{{ $schedule->route?->origin ?? 'Origin' }} to {{ $schedule->route?->destination ?? 'Destination' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $schedule->train?->train_name ?? 'Unassigned' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ \Illuminate\Support\Carbon::parse($schedule->departure_time)->format('h:i A') }}</td>
                                <td class="px-5 py-4 text-slate-600">PHP {{ number_format($schedule->fare, 2) }}</td>
                                <td class="px-5 py-4"><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ number_format($schedule->available_seats) }}</span></td>
                                @if (Auth::user()->isAdmin())
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('schedules.edit', $schedule) }}" class="rounded-md border border-slate-200 px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a>
                                            <form method="POST" action="{{ route('schedules.destroy', $schedule) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md border border-rose-200 px-3 py-1.5 font-semibold text-rose-700 transition hover:bg-rose-50">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-500">No schedules yet. Add trains and routes first, then create a departure.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">{{ $schedules->links() }}</div>
        </div>
    </div>
</x-app-layout>
