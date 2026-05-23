<?php

namespace App\Http\Controllers;

use App\Models\Route as TrainRoute;
use App\Models\Schedule;
use App\Models\Train;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['train', 'route'])->latest()->paginate(10);

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view('schedules.create', [
            'trains' => Train::orderBy('train_name')->get(),
            'routes' => TrainRoute::orderBy('origin')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Log::error('ScheduleController@store called', [
            'user_id' => auth()->id(),
            'is_admin' => auth()->check() ? auth()->user()->isAdmin() : null,
            'input' => $request->except(['_token']),
        ]);

        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'train_id' => ['required', 'exists:trains,id'],
            'route_id' => ['required', 'exists:routes,id'],
            'departure_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'arrival_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'fare' => ['required', 'numeric', 'min:0'],
            'available_seats' => ['required', 'integer', 'min:0'],
        ]);

        $validated['departure_time'] = $this->normalizeTime($validated['departure_time']);
        $validated['arrival_time'] = $this->normalizeTime($validated['arrival_time']);

        Schedule::create($validated);

        return redirect()->route('schedules.index')->with('status', 'Schedule created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('schedules.index');
    }

    public function edit(string $id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $schedule = Schedule::findOrFail($id);

        return view('schedules.edit', [
            'schedule' => $schedule,
            'trains' => Train::orderBy('train_name')->get(),
            'routes' => TrainRoute::orderBy('origin')->get(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'train_id' => ['required', 'exists:trains,id'],
            'route_id' => ['required', 'exists:routes,id'],
            'departure_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'arrival_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'fare' => ['required', 'numeric', 'min:0'],
            'available_seats' => ['required', 'integer', 'min:0'],
        ]);

        $validated['departure_time'] = $this->normalizeTime($validated['departure_time']);
        $validated['arrival_time'] = $this->normalizeTime($validated['arrival_time']);

        $schedule->update($validated);

        return redirect()->route('schedules.index')->with('status', 'Schedule updated successfully.');
    }

    public function destroy(string $id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        Schedule::findOrFail($id)->delete();

        return redirect()->route('schedules.index')->with('status', 'Schedule deleted successfully.');
    }

    private function normalizeTime(string $time): string
    {
        return Carbon::createFromFormat(str_contains($time, ':') ? (substr_count($time, ':') === 2 ? 'H:i:s' : 'H:i') : 'H:i', $time)
            ->format('H:i:s');
    }
}
