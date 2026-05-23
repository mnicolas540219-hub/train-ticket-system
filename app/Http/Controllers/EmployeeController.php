<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        // show reservations with pending payment and allow filtering
        $query = Reservation::with(['user', 'schedule.train', 'schedule.route'])
            ->where('payment_status', 'pending');

        if ($q = request()->query('q')) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('full_name', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        if ($scheduleId = request()->query('schedule_id')) {
            $query->where('schedule_id', $scheduleId);
        }

        if ($date = request()->query('date')) {
            // filter by reservation created date (Y-m-d)
            $query->whereDate('created_at', $date);
        }

        $reservations = $query->latest()->paginate(20)->appends(request()->query());

        $schedules = \App\Models\Schedule::with(['route', 'train'])->orderBy('departure_time')->get();

        return view('employee.reservations', compact('reservations', 'schedules'));
    }

    public function issue(Request $request, $id)
    {
        $reservation = Reservation::with('schedule')->findOrFail($id);

        // generate a one-time ticket reference code in the format XXXX-XXXX
        $code = strtoupper(bin2hex(random_bytes(4)));
        $ticketReference = substr($code, 0, 4) . '-' . substr($code, 4, 4);

        $reservation->payment_status = 'paid';
        $reservation->ticket_status = 'unused';
        $reservation->qr_code = $ticketReference;
        // If no seat assigned, leave as Unassigned — station staff can assign later
        $reservation->save();

        Payment::create([
            'reservation_id' => $reservation->id,
            'amount' => $reservation->schedule?->fare ?? 0,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
        ]);

        return redirect()->route('reservations.archive')->with('status', "Ticket issued and payment recorded. Reference: {$ticketReference}");
    }
}
