<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['from', 'to', 'guest_name', 'portal', 'booked_at'];

    protected $casts = [
        'from'      => 'date',
        'to'        => 'date',
        'booked_at' => 'date',
    ];
}
