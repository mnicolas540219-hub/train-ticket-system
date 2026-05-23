<?php

namespace App\Http\Controllers;

use App\Models\Train;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    public function index()
    {
        $trains = Train::latest()->paginate(10);

        return view('trains.index', compact('trains'));
    }

    public function create()
    {
        return view('trains.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'train_name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        Train::create($validated);

        return redirect()->route('trains.index')->with('status', 'Train created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('trains.edit', $id);
    }

    public function edit(string $id)
    {
        $train = Train::findOrFail($id);

        return view('trains.edit', compact('train'));
    }

    public function update(Request $request, string $id)
    {
        $train = Train::findOrFail($id);

        $validated = $request->validate([
            'train_name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $train->update($validated);

        return redirect()->route('trains.index')->with('status', 'Train updated successfully.');
    }

    public function destroy(string $id)
    {
        Train::findOrFail($id)->delete();

        return redirect()->route('trains.index')->with('status', 'Train deleted successfully.');
    }
}
