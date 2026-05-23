<?php

use App\Models\User;
use App\Models\Reservation;
use App\Models\Train;
use App\Models\Route as TrainRoute;
use App\Models\Schedule;
use App\Models\Payment;

it('denies access to non-employee users', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get(route('station.reservations'));

    $response->assertStatus(302);
    $response->assertRedirect('/');
});

it('allows employee to view pending reservations and issue ticket', function () {
    // create employee
    $employee = User::factory()->create(['role' => 'employee']);

    // create route, train, schedule
    $route = TrainRoute::create(['origin' => 'Origin', 'destination' => 'Destination']);
    $train = Train::create(['train_name' => 'T1', 'capacity' => 100]);

    $schedule = Schedule::create([
        'train_id' => $train->id,
        'route_id' => $route->id,
        'departure_time' => '08:00:00',
        'arrival_time' => '10:00:00',
        'fare' => 123.45,
        'available_seats' => 10,
    ]);

    // create a reserving user and reservation with pending payment
    $customer = User::factory()->create(['role' => 'user']);

    $reservation = Reservation::create([
        'user_id' => $customer->id,
        'schedule_id' => $schedule->id,
        'full_name' => 'Test Passenger',
        'seat_number' => 'Unassigned',
        'payment_status' => 'pending',
        'ticket_status' => 'unused'
    ]);

    // employee can view list
    $resp = $this->actingAs($employee)->get(route('station.reservations'));
    $resp->assertStatus(200);
    $resp->assertSeeText('Test Passenger');

    // issue ticket
    $issueResp = $this->actingAs($employee)->post(route('station.reservations.issue', $reservation));
    $issueResp->assertStatus(302);
    $issueResp->assertRedirect(route('station.reservations'));
    $issueResp->assertSessionHas('status');

    $reservation->refresh();
    expect($reservation->payment_status)->toBe('paid');
    expect($reservation->qr_code)->toMatch('/^[A-F0-9]{4}-[A-F0-9]{4}$/');

    $this->assertDatabaseHas('payments', ['reservation_id' => $reservation->id, 'payment_status' => 'paid']);
    $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'qr_code' => $reservation->qr_code]);
});
