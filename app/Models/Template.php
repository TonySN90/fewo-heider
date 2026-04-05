<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function sections(): HasMany
    {
        return $this->hasMany(TemplateSection::class)->orderBy('sort_order');
    }

    public function activate(): void
    {
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }

    public static function active(): ?self
    {
        return static::with('sections')->where('is_active', true)->first();
    }
}
