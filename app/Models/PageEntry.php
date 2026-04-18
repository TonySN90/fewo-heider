<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class PageEntry extends Model
{
    use HasSEO;

    protected $fillable = ['page_id', 'title', 'title_en', 'slug', 'cover_image', 'image_position', 'sort_order'];

    public function localizedTitle(): string
    {
        return app()->getLocale() === 'en' && $this->title_en
            ? $this->title_en
            : $this->title;
    }

    protected static function booted(): void
    {
        static::creating(function (self $entry) {
            if (empty($entry->slug)) {
                $entry->slug = Str::slug($entry->title);
            }
        });
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(PageEntryBlock::class)->orderBy('sort_order');
    }

    public function getDynamicSEOData(): SEOData
    {
        $description = null;

        if ($this->relationLoaded('blocks')) {
            /** @var PageEntryBlock|null $block */
            $block = $this->blocks->firstWhere('type', 'text');
            /** @var string|null $text */
            $text = $block?->getAttribute('content');
            if ($text) {
                $description = mb_strlen($text) > 160
                    ? mb_substr($text, 0, 157) . '…'
                    : $text;
            }
        }

        return new SEOData(
            title: $this->title,
            description: $description,
            image: $this->cover_image ? Storage::url($this->cover_image) : null,
        );
    }
}
