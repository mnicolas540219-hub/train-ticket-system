<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Fleet management</p>
            <h2 class="text-2xl font-semibold text-slate-950">Edit Train</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('trains.update', $train) }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-5">
                <div>
                    <x-input-label for="train_name" value="Train Name" />
                    <x-text-input id="train_name" name="train_name" type="text" class="mt-2 block w-full" value="{{ old('train_name', $train->train_name) }}" required autofocus />
                    <x-input-error :messages="$errors->get('train_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="capacity" value="Capacity" />
                    <x-text-input id="capacity" name="capacity" type="number" min="1" class="mt-2 block w-full" value="{{ old('capacity', $train->capacity) }}" required />
                    <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('trains.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
                <x-primary-button>Update Train</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
