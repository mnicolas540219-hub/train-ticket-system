<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Network planning</p>
            <h2 class="text-2xl font-semibold text-slate-950">Add Route</h2>
        </div>
    </x-slot>

    @php
        $stops = old('stops', ['']);
    @endphp

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('routes.store') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm" x-data="{ stops: @js($stops) }">
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="origin" value="Origin" />
                    <x-text-input id="origin" name="origin" type="text" class="mt-2 block w-full" value="{{ old('origin') }}" required autofocus />
                    <x-input-error :messages="$errors->get('origin')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="destination" value="Destination" />
                    <x-text-input id="destination" name="destination" type="text" class="mt-2 block w-full" value="{{ old('destination') }}" required />
                    <x-input-error :messages="$errors->get('destination')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-slate-950">Stops Between Origin and Destination</h3>
                        <p class="text-sm text-slate-500">Add stations where this train can stop before reaching the final destination.</p>
                    </div>
                    <button type="button" @click="stops.push('')" class="shrink-0 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Add Stop
                    </button>
                </div>

                <div class="mt-4 grid gap-3">
                    <template x-for="(stop, index) in stops" :key="index">
                        <div class="flex gap-2">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-white text-sm font-semibold text-slate-500 ring-1 ring-slate-200" x-text="index + 1"></div>
                            <input type="text" name="stops[]" x-model="stops[index]" class="block w-full rounded-md border-slate-300 bg-white shadow-sm transition focus:border-slate-700 focus:ring-slate-700" placeholder="Station name">
                            <button type="button" @click="stops.splice(index, 1); if (stops.length === 0) stops.push('')" class="rounded-md border border-rose-200 bg-white px-3 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-50">
                                Remove
                            </button>
                        </div>
                    </template>
                    <x-input-error :messages="$errors->get('stops.*')" class="mt-1" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('routes.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
                <x-primary-button>Save Route</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
