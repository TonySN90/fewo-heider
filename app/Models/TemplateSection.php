<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateSection extends Model
{
    protected $fillable = ['template_id', 'section_key', 'is_visible', 'sort_order'];

    protected $casts = ['is_visible' => 'boolean'];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function content(): HasMany
    {
        return $this->hasMany(TemplateSectionContent::class, 'template_section_id');
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class, 'template_section_id')->orderBy('sort_order');
    }

    /** Gibt einen einzelnen Feldwert zurück, mit optionalem Fallback. */
    public function field(string $key, string $default = ''): string
    {
        return $this->content->firstWhere('field_key', $key)?->value ?? $default;
    }
}
