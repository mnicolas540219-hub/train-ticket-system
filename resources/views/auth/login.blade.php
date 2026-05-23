<x-guest-layout>
    <!-- Session Status -->
    @php
        $admin = $admin ?? request('admin');
        $employee = $employee ?? request('employee');
    @endphp

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 rounded-lg border border-slate-200 bg-slate-50 p-4 text-slate-800">
        <h2 class="text-xl font-semibold">
            {{ $admin ? __('Admin Login') : ($employee ? __('Employee Login') : __('Customer Login')) }}
        </h2>
        <p class="mt-2 text-sm text-slate-600">Create an account before reserving a seat, then sign in to book your train.</p>
        @if ($admin)
            <p class="mt-2 text-sm text-slate-600">Use your administrator username (admin) and password to access admin features.</p>
        @elseif ($employee)
            <p class="mt-2 text-sm text-slate-600">Use your employee username and password to access station features.</p>
        @endif
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        @if ($admin)
            <input type="hidden" name="admin" value="1">
        @endif
        @if ($employee)
            <input type="hidden" name="employee" value="1">
        @endif

        @if ($admin || $employee)
            <!-- Username for Admin/Employee -->
            <div>
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>
        @else
            <!-- Email or Username for Customer -->
            <div>
                <x-input-label for="login" :value="__('Email or Username')" />
                <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('login')" class="mt-2" />
            </div>
        @endif

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        @if (! $admin)
            <!-- Remember Me (only for customers) -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-700" name="remember">
                    <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
                </label>
            </div>
        @endif

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="rounded-md text-sm font-medium text-slate-600 underline transition hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-4 text-slate-700">
        @if ($admin)
            <p class="text-sm">Not an admin? <a href="{{ route('login') }}" class="font-semibold text-cyan-700 underline">Go back to customer login</a>.</p>
            <p class="mt-2 text-sm">Need to sign in as an employee? <a href="{{ route('employee.login') }}" class="font-semibold text-cyan-700 underline">Login as employee</a>.</p>
        @elseif ($employee)
            <p class="text-sm">Not an employee? <a href="{{ route('login') }}" class="font-semibold text-cyan-700 underline">Go back to customer login</a>.</p>
            <p class="mt-2 text-sm">Need to sign in as an admin? <a href="{{ route('admin.login') }}" class="font-semibold text-cyan-700 underline">Login as admin</a>.</p>
        @else
            <p class="text-sm">Don't have an account? <a href="{{ route('register') }}" class="font-semibold text-cyan-700 underline">Create account</a>.</p>
            <p class="mt-2 text-sm">Need to sign in as an admin? <a href="{{ route('admin.login') }}" class="font-semibold text-cyan-700 underline">Login as admin</a>.</p>
            <p class="mt-2 text-sm">Are you a station employee? <a href="{{ route('employee.login') }}" class="font-semibold text-cyan-700 underline">Login as employee</a>.</p>
        @endif
    </div>
</x-guest-layout>
