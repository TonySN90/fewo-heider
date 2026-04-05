<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateSectionContent extends Model
{
    protected $table = 'template_section_content';

    protected $fillable = ['template_section_id', 'field_key', 'value'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(TemplateSection::class, 'template_section_id');
    }
}