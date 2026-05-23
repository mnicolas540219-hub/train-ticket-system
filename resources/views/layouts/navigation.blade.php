<nav x-data="{ open: false }" class="relative z-10 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex min-w-0 items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-slate-950 text-white">
                        <x-application-logo class="h-6 w-6 fill-current" />
                    </span>
                    <span class="hidden text-sm font-semibold text-slate-950 sm:block">TicketRail</span>
                </a>

                <div class="hidden space-x-6 sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if (Auth::user()->isAdmin())
                        <x-nav-link :href="route('trains.index')" :active="request()->routeIs('trains.*')">
                            {{ __('Trains') }}
                        </x-nav-link>
                        <x-nav-link :href="route('routes.index')" :active="request()->routeIs('routes.*')">
                            {{ __('Routes') }}
                        </x-nav-link>
                        <x-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                            {{ __('Schedules') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.*')">
                            {{ __('Reservations') }}
                        </x-nav-link>
                        <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
                            {{ __('Employees') }}
                        </x-nav-link>
                    @elseif (Auth::user()->role === 'employee')
                        <x-nav-link :href="route('station.reservations')" :active="request()->routeIs('station.*')">
                            {{ __('Station') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                            {{ __('Schedules') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.*')">
                            {{ __('My Bookings') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-cyan-100 text-xs font-bold text-cyan-700">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-slate-700 transition hover:bg-slate-100 hover:text-slate-950 focus:outline-none focus:bg-slate-100">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200 sm:hidden">
        <div class="space-y-1 px-2 py-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('trains.index')" :active="request()->routeIs('trains.*')">{{ __('Trains') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('routes.index')" :active="request()->routeIs('routes.*')">{{ __('Routes') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">{{ __('Schedules') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.*')">{{ __('Reservations') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">{{ __('Employees') }}</x-responsive-nav-link>
            @elseif (Auth::user()->role === 'employee')
                <x-responsive-nav-link :href="route('station.reservations')" :active="request()->routeIs('station.*')">{{ __('Station') }}</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">{{ __('Schedules') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.*')">{{ __('My Bookings') }}</x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-slate-200 pb-3 pt-4">
            <div class="px-4">
                <div class="font-medium text-slate-900">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:bg-slate-50">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
