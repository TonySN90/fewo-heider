<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    /** @return HasMany<TemplateSection, $this> */
    public function sections(): HasMany
    {
        return $this->hasMany(TemplateSection::class)->orderBy('sort_order');
    }

    public function findSection(string $sectionKey): TemplateSection
    {
        /** @var TemplateSection */
        return $this->sections()->where('section_key', $sectionKey)->firstOrFail();
    }

    public function getSection(string $sectionKey): ?TemplateSection
    {
        /** @var TemplateSection|null */
        return $this->sections->firstWhere('section_key', $sectionKey);
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
