<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    public function schedules()
{
    return $this->hasMany(Schedule::class);
}

protected $fillable = [
    'train_name',
    'capacity'
];
}

