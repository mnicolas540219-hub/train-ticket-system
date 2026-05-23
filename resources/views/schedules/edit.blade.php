<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Trip operations</p>
            <h2 class="text-2xl font-semibold text-slate-950">Edit Schedule</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('schedules.update', $schedule) }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="train_id" value="Train" />
                    <select id="train_id" name="train_id" class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-700 focus:ring-slate-700" required>
                        @foreach ($trains as $train)
                            <option value="{{ $train->id }}" @selected(old('train_id', $schedule->train_id) == $train->id)>{{ $train->train_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('train_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="route_id" value="Route" />
                    <select id="route_id" name="route_id" class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-700 focus:ring-slate-700" required>
                        @foreach ($routes as $route)
                            <option value="{{ $route->id }}" @selected(old('route_id', $schedule->route_id) == $route->id)>{{ $route->origin }} to {{ $route->destination }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('route_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="departure_time" value="Daily Departure Time" />
                    <x-text-input id="departure_time" name="departure_time" type="time" step="60" class="mt-2 block w-full" value="{{ old('departure_time', \Illuminate\Support\Carbon::parse($schedule->departure_time)->format('H:i')) }}" required />
                    <x-input-error :messages="$errors->get('departure_time')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="arrival_time" value="Daily Arrival Time" />
                    <x-text-input id="arrival_time" name="arrival_time" type="time" step="60" class="mt-2 block w-full" value="{{ old('arrival_time', \Illuminate\Support\Carbon::parse($schedule->arrival_time)->format('H:i')) }}" required />
                    <x-input-error :messages="$errors->get('arrival_time')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="fare" value="Fare" />
                    <x-text-input id="fare" name="fare" type="number" step="0.01" min="0" class="mt-2 block w-full" value="{{ old('fare', $schedule->fare) }}" required />
                    <x-input-error :messages="$errors->get('fare')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="available_seats" value="Available Seats" />
                    <x-text-input id="available_seats" name="available_seats" type="number" min="0" class="mt-2 block w-full" value="{{ old('available_seats', $schedule->available_seats) }}" required />
                    <x-input-error :messages="$errors->get('available_seats')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('schedules.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
                <x-primary-button style="position:relative;z-index:10">Update Schedule</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
