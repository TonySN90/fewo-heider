<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = ['name', 'from', 'to', 'price_per_night', 'min_nights', 'sort_order', 'badge_color'];

    protected $casts = [
        'from' => 'date',
        'to'   => 'date',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('from');
    }
}
