<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'from', 'to', 'guest_name', 'portal', 'booked_at'];

    protected $casts = [
        'from'      => 'date',
        'to'        => 'date',
        'booked_at' => 'date',
    ];
}
