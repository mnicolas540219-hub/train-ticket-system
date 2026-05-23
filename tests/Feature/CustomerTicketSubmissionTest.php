<?php

use App\Models\User;
use App\Models\Reservation;
use App\Models\Train;
use App\Models\Route as TrainRoute;
use App\Models\Schedule;

it('accepts a valid unused ticket and replies safe travels', function () {
    $user = User::factory()->create(['role' => 'user', 'email_verified_at' => now()]);

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

    $reservation = Reservation::create([
        'user_id' => $user->id,
        'schedule_id' => $schedule->id,
        'full_name' => 'Test Passenger',
        'seat_number' => 'Unassigned',
        'payment_status' => 'paid',
        'ticket_status' => 'unused',
        'qr_code' => 'ABCD-1234',
    ]);

    $response = $this->actingAs($user)->post(route('dashboard.ticket.submit'), ['ticket_code' => 'abcd-1234']);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success', 'Safe travels');

    $reservation->refresh();
    expect($reservation->ticket_status)->toBe('used');
});

it('rejects a ticket that has already been used', function () {
    $user = User::factory()->create(['role' => 'user', 'email_verified_at' => now()]);

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

    $reservation = Reservation::create([
        'user_id' => $user->id,
        'schedule_id' => $schedule->id,
        'full_name' => 'Test Passenger',
        'seat_number' => 'Unassigned',
        'payment_status' => 'paid',
        'ticket_status' => 'used',
        'qr_code' => 'ABCD-1234',
    ]);

    $response = $this->actingAs($user)->post(route('dashboard.ticket.submit'), ['ticket_code' => 'ABCD-1234']);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('error', 'This ticket has already been used and is invalid.');
});
