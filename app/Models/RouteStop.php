<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteStop extends Model
{
    protected $fillable = [
        'route_id',
        'station_name',
        'stop_order',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }
}
