<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateSection extends Model
{
    protected $fillable = ['template_id', 'section_key', 'is_visible', 'sort_order'];

    protected $casts = ['is_visible' => 'boolean'];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
