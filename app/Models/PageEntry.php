<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PageEntry extends Model
{
    protected $fillable = ['page_id', 'title', 'slug', 'cover_image', 'sort_order'];

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
}
