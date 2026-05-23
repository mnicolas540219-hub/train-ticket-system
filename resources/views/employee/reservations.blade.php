<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Station</p>
                <h2 class="text-2xl font-semibold text-slate-950">Pending Reservations</h2>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
        @endif

        @if(session('ticket_reference'))
            <div id="ticket-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 p-4">
                <div class="w-full max-w-xl rounded-3xl bg-white p-6 shadow-2xl ring-1 ring-slate-200">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Ticket issued</p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-950">Present this ticket to the passenger</h2>
                        </div>
                        <button type="button" onclick="document.getElementById('ticket-modal').classList.add('hidden')" class="rounded-full bg-slate-100 p-2 text-slate-600 transition hover:bg-slate-200">×</button>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Reference</p>
                            <p class="mt-2 text-xl font-semibold text-slate-950">{{ session('ticket_reference') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Passenger</p>
                            <p class="mt-2 text-base font-semibold text-slate-950">{{ session('ticket_passenger') }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ session('ticket_trip') }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ session('ticket_train') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" onclick="document.getElementById('ticket-modal').classList.add('hidden')" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Close</button>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const modal = document.getElementById('ticket-modal');
                    if (modal) {
                        modal.classList.remove('hidden');
                    }
                });
            </script>
        @endif

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-950">Reservations awaiting payment</h3>
                        <p class="text-sm text-slate-500">Issue tickets and record cash payments at the station. Issued reservations are also shown here with their issue time.</p>
                    </div>
                    <a href="{{ route('reservations.archive') }}" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Archive</a>
                </div>
            </div>

            <div class="px-5 py-4">
                <form method="GET" action="{{ route('station.reservations') }}" class="flex gap-3 items-end">
                    <div>
                        <label class="text-xs text-slate-500">Name or email</label>
                        <input type="text" name="q" value="{{ request('q') }}" class="mt-1 rounded-md border-slate-300 px-3 py-2 text-sm" placeholder="Search name or email">
                    </div>

                    <div>
                        <label class="text-xs text-slate-500">Schedule</label>
                        <select name="schedule_id" class="mt-1 rounded-md border-slate-300 px-3 py-2 text-sm">
                            <option value="">All schedules</option>
                            @foreach($schedules as $s)
                                <option value="{{ $s->id }}" @selected(request('schedule_id') == $s->id)>{{ $s->route?->origin }} to {{ $s->route?->destination }} - {{ \Illuminate\Support\Carbon::parse($s->departure_time)->format('h:i A') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs text-slate-500">Date</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="mt-1 rounded-md border-slate-300 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <button type="submit" class="rounded-md bg-slate-950 px-4 py-2 text-xs font-semibold text-white">Filter</button>
                        <a href="{{ route('station.reservations') }}" class="ml-2 text-sm text-slate-600">Reset</a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Passenger</th>
                            <th class="px-5 py-3">Trip</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Time</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($reservations as $reservation)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-950">{{ $reservation->full_name ?: $reservation->user?->name ?: 'Passenger' }}</p>
                                    <p class="text-xs text-slate-500">{{ $reservation->user?->email ?? '' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <p>{{ $reservation->schedule?->route?->origin ?? 'Origin' }} to {{ $reservation->schedule?->route?->destination ?? 'Destination' }}</p>
                                    <p class="text-xs text-slate-500">{{ $reservation->schedule?->train?->train_name ?? 'Train' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $reservation->issued_at?->format('m-d-Y') ?? $reservation->updated_at?->format('m-d-Y') ?? '—' }}
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $reservation->issued_at?->format('h:i A') ?? $reservation->updated_at?->format('h:i A') ?? '—' }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $reservation->payment_status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' }}">
                                        {{ $reservation->payment_status === 'pending' ? 'Issue requested' : ucfirst($reservation->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @if($reservation->payment_status === 'pending')
                                        <form method="POST" action="{{ route('station.reservations.issue', $reservation) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-800">
                                                Issue ticket
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Issued</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-500">No pending reservations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">{{ $reservations->links() }}</div>
        </div>
    </div>
</x-app-layout>
