<?php

namespace App\Http\Controllers;

use App\Models\Route as TrainRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    public function index()
    {
        $routes = TrainRoute::with('stops')->latest()->paginate(10);

        return view('routes.index', compact('routes'));
    }

    public function create()
    {
        return view('routes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'stops' => ['nullable', 'array'],
            'stops.*' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            $route = TrainRoute::create([
                'origin' => $validated['origin'],
                'destination' => $validated['destination'],
            ]);

            $this->syncStops($route, $validated['stops'] ?? []);
        });

        return redirect()->route('routes.index')->with('status', 'Route created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('routes.edit', $id);
    }

    public function edit(string $id)
    {
        $route = TrainRoute::with('stops')->findOrFail($id);

        return view('routes.edit', compact('route'));
    }

    public function update(Request $request, string $id)
    {
        $route = TrainRoute::findOrFail($id);

        $validated = $request->validate([
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'stops' => ['nullable', 'array'],
            'stops.*' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($route, $validated) {
            $route->update([
                'origin' => $validated['origin'],
                'destination' => $validated['destination'],
            ]);

            $this->syncStops($route, $validated['stops'] ?? []);
        });

        return redirect()->route('routes.index')->with('status', 'Route updated successfully.');
    }

    public function destroy(string $id)
    {
        TrainRoute::findOrFail($id)->delete();

        return redirect()->route('routes.index')->with('status', 'Route deleted successfully.');
    }

    private function syncStops(TrainRoute $route, array $stops): void
    {
        $route->stops()->delete();

        collect($stops)
            ->map(fn ($stop) => trim((string) $stop))
            ->filter()
            ->values()
            ->each(function ($stop, $index) use ($route) {
                $route->stops()->create([
                    'station_name' => $stop,
                    'stop_order' => $index + 1,
                ]);
            });
    }
}
