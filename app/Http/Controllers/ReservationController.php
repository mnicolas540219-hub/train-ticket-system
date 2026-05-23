<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $reservations = Reservation::with(['user', 'schedule.train', 'schedule.route'])
                ->where('payment_status', 'pending')
                ->latest()
                ->paginate(10);
        } else {
            $reservations = auth()->user()->reservations()->with(['schedule.train', 'schedule.route'])->latest()->paginate(10);
        }

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $schedules = Schedule::with(['train', 'route'])
            ->where('available_seats', '>', 0)
            ->orderBy('departure_time')
            ->get();

        return view('reservations.create', compact('schedules'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'schedule_id' => ['required', 'exists:schedules,id'],
        'full_name' => ['required', 'string', 'max:255'],
    ]);

    $schedule = Schedule::findOrFail($validated['schedule_id']);

    if ($schedule->available_seats <= 0) {
        return back()->with('error', 'No seats available');
    }

    Reservation::create([
        'user_id' => auth()->id(),
        'schedule_id' => $schedule->id,
        'full_name' => $validated['full_name'],
        'seat_number' => 'Unassigned',
        'payment_status' => 'pending',
        'ticket_status' => 'unused'
    ]);

    $schedule->decrement('available_seats');

    return back()->with('success', 'Reservation is done. Payment is pending and must be completed in cash at the station with a valid ID.');
}

    public function submitTicket(Request $request)
    {
        $validated = $request->validate([
            'ticket_code' => ['required', 'string', 'regex:/^[A-F0-9]{4}-[A-F0-9]{4}$/i'],
        ]);

        $ticketCode = strtoupper($validated['ticket_code']);

        // Look up the reservation globally so we can show owner/used status
        $reservation = Reservation::where('qr_code', $ticketCode)->first();

        if (! $reservation) {
            // Try a relaxed lookup ignoring dashes/case in case the stored format differs.
            $codeOnly = str_replace('-', '', $ticketCode);
            $fallback = Reservation::whereRaw("REPLACE(UPPER(qr_code), '-', '') = ?", [strtoupper($codeOnly)])->first();

            if ($fallback && $fallback->ticket_status !== 'unused') {
                return redirect()->route('dashboard')->with([
                    'ticket_submit_status' => 'error',
                    'ticket_submit_message' => 'This ticket has already been used.',
                    'ticket_code' => $ticketCode,
                    'ticket_used' => true,
                    'ticket_owner' => $fallback->full_name ?: $fallback->user?->name,
                ]);
            }

            return redirect()->route('dashboard')->with([
                'ticket_submit_status' => 'error',
                'ticket_submit_message' => 'Invalid ticket reference.',
                'ticket_code' => $ticketCode,
                'ticket_used' => false,
            ]);
        }

        // If the ticket exists but belongs to someone else, show who it belongs to
        if ($reservation->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with([
                'ticket_submit_status' => 'error',
                'ticket_submit_message' => $reservation->ticket_status !== 'unused' ? 'This ticket has already been used.' : 'This ticket belongs to another passenger.',
                'ticket_code' => $ticketCode,
                'ticket_used' => $reservation->ticket_status !== 'unused',
                'ticket_owner' => $reservation->full_name ?: $reservation->user?->name,
            ]);
        }

        // At this point the ticket belongs to the current user
        if ($reservation->ticket_status !== 'unused') {
            return redirect()->route('dashboard')->with([
                'ticket_submit_status' => 'error',
                'ticket_submit_message' => 'This ticket has already been used.',
                'ticket_code' => $ticketCode,
                'ticket_used' => true,
                'ticket_owner' => $reservation->full_name ?: $reservation->user?->name,
            ]);
        }

        $reservation->ticket_status = 'used';
        $reservation->save();

        return redirect()->route('dashboard')->with([
            'ticket_submit_status' => 'success',
            'ticket_submit_message' => 'Safe travels',
            'ticket_code' => $ticketCode,
            'ticket_used' => false,
            'ticket_owner' => $reservation->full_name ?: $reservation->user?->name,
        ]);
    }

    /**
     * Public ticket validation for unauthenticated gate screen.
     */
    public function publicValidate(Request $request)
    {
        $rawCode = strtoupper(trim($request->input('ticket_code', '')));
        $codeOnly = preg_replace('/[^A-Z0-9]/', '', $rawCode);

        if (strlen($codeOnly) !== 8) {
            return redirect('/')->with([
                'ticket_validate_status' => 'error',
                'ticket_validate_message' => 'Invalid ticket reference.',
                'ticket_code' => $rawCode,
            ]);
        }

        $ticketCode = substr($codeOnly, 0, 4) . '-' . substr($codeOnly, 4, 4);
        $reservation = Reservation::where('qr_code', $ticketCode)->first();

        if (! $reservation) {
            // relaxed lookup to detect used tickets stored with different formatting
            $codeOnly = str_replace('-', '', $ticketCode);
            $fallback = Reservation::whereRaw("REPLACE(UPPER(qr_code), '-', '') = ?", [strtoupper($codeOnly)])->first();

            if ($fallback && $fallback->ticket_status !== 'unused') {
                return redirect('/')->with([
                    'ticket_validate_status' => 'error',
                    'ticket_validate_message' => 'This ticket has already been used.',
                    'ticket_code' => $ticketCode,
                    'ticket_full_name' => $fallback->full_name ?? null,
                    'ticket_used' => true,
                ]);
            }

            return redirect('/')->with([
                'ticket_validate_status' => 'error',
                'ticket_validate_message' => 'Invalid ticket reference.',
                'ticket_code' => $ticketCode,
                'ticket_used' => false,
            ]);
        }

        if ($reservation->ticket_status !== 'unused') {
            return redirect('/')->with([
                'ticket_validate_status' => 'error',
                'ticket_validate_message' => 'This ticket has already been used.',
                'ticket_code' => $ticketCode,
                'ticket_full_name' => $reservation->full_name ?? null,
                'ticket_used' => true,
            ]);
        }

        $reservation->ticket_status = 'used';
        $reservation->save();

        return redirect('/')->with([
            'ticket_validate_status' => 'success',
            'ticket_validate_message' => 'Valid ticket — Safe travels',
            'ticket_code' => $ticketCode,
            'ticket_full_name' => $reservation->full_name ?? null,
            'ticket_used' => false,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function archive()
    {
        $reservations = Reservation::with(['user', 'schedule.train', 'schedule.route'])
            ->where('payment_status', 'paid')
            ->latest()
            ->paginate(10);

        return view('reservations.archive', compact('reservations'));
    }

    public function show(string $id)
    {
        return redirect()->route('reservations.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('reservations.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'payment_status' => ['required', 'in:pending,paid'],
            'ticket_status' => ['required', 'in:unused,used,cancelled'],
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.index')->with('status', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Reservation::findOrFail($id)->delete();

        return redirect()->route('reservations.index')->with('status', 'Reservation deleted successfully.');
    }
}
