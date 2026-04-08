<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $template_section_id
 * @property string $filename
 * @property string|null $caption
 * @property int $sort_order
 */
class GalleryImage extends Model
{
    protected $fillable = ['template_section_id', 'filename', 'caption', 'sort_order'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(TemplateSection::class, 'template_section_id');
    }

    public function url(): string
    {
        return '/storage/gallery/'.$this->filename;
    }
}
