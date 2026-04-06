<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = ['tenant_id', 'page_group_id', 'title', 'slug', 'description', 'cover_image', 'is_visible', 'sort_order'];

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
}
