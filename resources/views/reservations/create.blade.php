<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Booking operations</p>
            <h2 class="text-2xl font-semibold text-slate-950">New Reservation</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-5 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('reservations.store') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="grid gap-5">
                <div>
                    <x-input-label for="schedule_id" value="Schedule" />
                    <select id="schedule_id" name="schedule_id" class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-700 focus:ring-slate-700" required>
                        <option value="">Select available trip</option>
                        @foreach ($schedules as $schedule)
                            <option value="{{ $schedule->id }}" @selected(old('schedule_id') == $schedule->id)>
                                {{ $schedule->route?->origin ?? 'Origin' }} to {{ $schedule->route?->destination ?? 'Destination' }} - {{ $schedule->train?->train_name ?? 'Train' }} - {{ \Illuminate\Support\Carbon::parse($schedule->departure_time)->format('h:i A') }} daily - {{ $schedule->available_seats }} seats
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('schedule_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="full_name" value="Full Name" />
                    <x-text-input id="full_name" name="full_name" type="text" class="mt-2 block w-full" value="{{ old('full_name') }}" placeholder="Your full name" required />
                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                </div>
            </div>

            <p class="mt-4 text-sm text-slate-500">Please bring a valid ID when boarding. Your seat is reserved now, and payment is due in cash at the station before boarding.</p>

            <div class="mt-6 flex items-center justify-between gap-3">
                <p class="text-sm text-slate-500">We’ll hold your booking until you arrive with a valid ID.</p>
                <div class="flex items-center gap-3">
                    <a href="javascript:history.back()" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Back</a>
                    <x-primary-button>Create Reservation</x-primary-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
