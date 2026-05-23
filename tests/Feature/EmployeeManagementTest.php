<?php

use App\Models\User;
// Removed duplicate uses declaration

beforeEach(function () {
    // create an admin and an employee user
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->employee = User::factory()->create(['role' => 'employee']);
    $this->user = User::factory()->create(['role' => 'user']);
});

it('allows admin to view employees index', function () {
    $response = $this->actingAs($this->admin)->get(route('employees.index'));
    $response->assertStatus(200);
    $response->assertSee('Employees');
});

it('prevents non-admins from accessing employee routes', function () {
    $routes = [
        route('employees.index'),
        route('employees.create'),
    ];

    foreach ($routes as $r) {
        $this->actingAs($this->user)->get($r)->assertRedirect('/');
        $this->actingAs($this->employee)->get($r)->assertRedirect('/');
    }
});

it('allows admin to create an employee', function () {
    $payload = [
        'username' => 'stationagent',
        'name' => 'Station Agent',
        'email' => 'agent@example.test',
        'password' => 'password123',
    ];

    $response = $this->actingAs($this->admin)->post(route('employees.store'), $payload);
    $response->assertRedirect(route('employees.index'));

    $this->assertDatabaseHas('users', [
        'username' => 'stationagent',
        'email' => 'agent@example.test',
        'role' => 'employee',
    ]);
});

it('allows admin to edit and update an employee', function () {
    $employee = User::factory()->create(['role' => 'employee']);

    $response = $this->actingAs($this->admin)->get(route('employees.edit', $employee));
    $response->assertStatus(200)->assertSee('Edit Employee');

    $update = [
        'username' => 'updated-username',
        'name' => 'Updated Name',
        'email' => 'updated@example.test',
    ];

    $this->actingAs($this->admin)->put(route('employees.update', $employee), $update)
        ->assertRedirect(route('employees.index'));

    $this->assertDatabaseHas('users', [
        'id' => $employee->id,
        'email' => 'updated@example.test',
    ]);
});

it('allows admin to delete an employee', function () {
    $employee = User::factory()->create(['role' => 'employee']);

    $this->actingAs($this->admin)->delete(route('employees.destroy', $employee))
        ->assertRedirect(route('employees.index'));

    $this->assertDatabaseMissing('users', ['id' => $employee->id]);
});
