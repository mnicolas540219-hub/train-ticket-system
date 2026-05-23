<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ReservationController;
use App\Models\Reservation;
use App\Models\Route as TrainRoute;
use App\Models\Schedule;
use App\Models\Train;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('trains', TrainController::class);
    Route::resource('routes', RouteController::class);
    Route::resource('schedules', ScheduleController::class)->except(['index', 'show']);
    Route::resource('employees', \App\Http\Controllers\EmployeeManagementController::class)->except(['show']);
});

Route::middleware(['auth', 'employee'])->group(function () {
    Route::get('station/reservations', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('station.reservations');
    Route::post('station/reservations/{reservation}/issue', [\App\Http\Controllers\EmployeeController::class, 'issue'])->name('station.reservations.issue');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('schedules', ScheduleController::class)->only(['index', 'show']);
    Route::get('reservations/archive', [\App\Http\Controllers\ReservationController::class, 'archive'])
        ->name('reservations.archive');
    Route::resource('reservations', ReservationController::class);
});

Route::get('/', function () {
    $featuredSchedules = Schedule::with(['train', 'route.stops'])
        ->orderBy('departure_time')
        ->take(4)
        ->get();

    return view('welcome', compact('featuredSchedules'));
});

// Public ticket validation endpoint for gate/landing screen — no auth required
Route::post('/ticket/validate', [\App\Http\Controllers\ReservationController::class, 'publicValidate'])
    ->name('ticket.validate');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        $stats = [
            'trains' => Train::count(),
            'routes' => TrainRoute::count(),
            'upcoming_schedules' => Schedule::count(),
            'reservations' => Reservation::count(),
            'available_seats' => Schedule::sum('available_seats'),
            'paid_tickets' => Reservation::where('payment_status', 'paid')->count(),
        ];

        $nextSchedules = Schedule::with(['train', 'route'])
            ->orderBy('departure_time')
            ->take(4)
            ->get();

        $recentReservations = Reservation::with(['user', 'schedule.train', 'schedule.route'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'nextSchedules', 'recentReservations'));
    }

    $availableSchedules = Schedule::with(['train', 'route'])
        ->where('available_seats', '>', 0)
        ->orderBy('departure_time')
        ->take(5)
        ->get();

    $myReservations = $user->reservations()
        ->with(['schedule.train', 'schedule.route'])
        ->latest()
        ->take(5)
        ->get();

    $dashboardStats = [
        'available_trips' => Schedule::where('available_seats', '>', 0)->count(),
        'my_bookings' => $user->reservations()->count(),
    ];

    return view('customer-dashboard', compact('availableSchedules', 'myReservations', 'dashboardStats'));
})->middleware(['auth'])->name('dashboard');

Route::post('/dashboard/ticket-submit', [ReservationController::class, 'submitTicket'])
    ->middleware(['auth'])
    ->name('dashboard.ticket.submit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
