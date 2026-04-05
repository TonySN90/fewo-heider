<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonPrice extends Model
{
    protected $fillable = ['season_id', 'name', 'from', 'to', 'price_per_night', 'min_nights', 'sort_order', 'badge_color'];

    protected $casts = [
        'from' => 'date',
        'to'   => 'date',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('from');
    }
}
