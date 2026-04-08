<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $template_id
 * @property string $section_key
 * @property bool $is_visible
 * @property int $sort_order
 * @property Collection<int, TemplateSectionContent> $content
 */
class TemplateSection extends Model
{
    protected $fillable = ['tenant_id', 'template_id', 'section_key', 'is_visible', 'sort_order'];

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
        $item = $this->content->firstWhere('field_key', $key);

        return $item !== null ? ($item->value ?? $default) : $default;
    }
}
