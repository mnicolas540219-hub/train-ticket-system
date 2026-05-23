<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

public function schedule(): BelongsTo
{
    return $this->belongsTo(Schedule::class);
}

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
    'user_id',
    'schedule_id',
    'full_name',
    'seat_number',
    'payment_status',
    'ticket_status',
    'qr_code'
];
}
