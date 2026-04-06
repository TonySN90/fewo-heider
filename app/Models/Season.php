<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $year
 * @property string $name
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Database\Eloquent\Collection<int, SeasonPrice> $prices
 */
class Season extends Model
{
    protected $fillable = ['year', 'name', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** @return HasMany<SeasonPrice, $this> */
    public function prices(): HasMany
    {
        return $this->hasMany(SeasonPrice::class);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('year', 'desc');
    }

    public function activate(): void
    {
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }
}
