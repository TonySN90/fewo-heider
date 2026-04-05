<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    protected $fillable = ['name', 'label', 'group', 'sort_order'];

    public static function forSelect(): array
    {
        return static::orderBy('group')->orderBy('sort_order')->orderBy('label')
            ->get()
            ->mapWithKeys(fn ($icon) => [$icon->name => $icon->label])
            ->toArray();
    }
}
