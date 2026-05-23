<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Network planning</p>
                <h2 class="text-2xl font-semibold text-slate-950">Routes</h2>
            </div>
            <a href="{{ route('routes.create') }}" class="inline-flex items-center justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Add Route</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
        @endif

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="font-semibold text-slate-950">Route Directory</h3>
                <p class="text-sm text-slate-500">Maintain origins, intermediate stops, and destinations for train schedules.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Origin</th>
                            <th class="px-5 py-3">Stops</th>
                            <th class="px-5 py-3">Destination</th>
                            <th class="px-5 py-3">Created</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($routes as $route)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4 font-semibold text-slate-950">{{ $route->origin }}</td>
                                <td class="px-5 py-4">
                                    @if ($route->stops->isNotEmpty())
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($route->stops as $stop)
                                                <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">{{ $stop->stop_order }}. {{ $stop->station_name }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-400">Direct</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $route->destination }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $route->created_at?->format('M d, Y') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('routes.edit', $route) }}" class="rounded-md border border-slate-200 px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a>
                                        <form method="POST" action="{{ route('routes.destroy', $route) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-rose-200 px-3 py-1.5 font-semibold text-rose-700 transition hover:bg-rose-50">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-slate-500">No routes yet. Add a route before creating schedules.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">{{ $routes->links() }}</div>
        </div>
    </div>
</x-app-layout>
