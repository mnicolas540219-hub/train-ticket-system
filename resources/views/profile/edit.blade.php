<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Account settings</p>
            <h2 class="text-2xl font-semibold text-slate-950">{{ __('Profile') }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
                <div class="max-w-xl">
                    @if (Auth::user()->id_photo)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-slate-950">Submitted ID</h3>
                            <p class="mt-1 text-sm text-slate-500">This is the ID image you uploaded during registration.</p>
                        </div>
                        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 p-4">
                            <img src="{{ Storage::url(Auth::user()->id_photo) }}" alt="Submitted ID" class="w-full rounded-xl object-contain" />
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                            No ID image is currently available for this account.
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-lg border border-rose-200 bg-white p-5 shadow-sm sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
