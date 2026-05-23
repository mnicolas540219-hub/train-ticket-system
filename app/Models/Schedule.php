<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public function train()
{
    return $this->belongsTo(Train::class);
}

public function route()
{
    return $this->belongsTo(Route::class);
}

public function reservations()
{
    return $this->hasMany(Reservation::class);
}
protected $fillable = [
    'train_id',
    'route_id',
    'departure_time',
    'arrival_time',
    'fare',
    'available_seats'
];
}
