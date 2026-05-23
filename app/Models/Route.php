<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
public function schedules(): HasMany
{
    return $this->hasMany(Schedule::class);
}

public function stops(): HasMany
{
    return $this->hasMany(RouteStop::class)->orderBy('stop_order');
}

protected $fillable = [
    'origin',
    'destination'
];
}
