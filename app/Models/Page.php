<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Page extends Model
{
    use HasSEO;

    protected $fillable = ['tenant_id', 'page_group_id', 'title', 'title_en', 'slug', 'description', 'description_en', 'cover_image', 'is_visible', 'sort_order', 'layout'];

    protected $casts = ['is_visible' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(PageGroup::class, 'page_group_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(PageEntry::class)->orderBy('sort_order');
    }

    public function localizedTitle(): string
    {
        return app()->getLocale() === 'en' && $this->title_en
            ? $this->title_en
            : $this->title;
    }

    public function localizedDescription(): ?string
    {
        return app()->getLocale() === 'en' && $this->description_en
            ? $this->description_en
            : $this->description;
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->localizedTitle(),
            description: $this->localizedDescription(),
            image: $this->cover_image ? Storage::url($this->cover_image) : null,
        );
    }
}
