<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class PageGroup extends Model
{
    use HasSEO;

    protected $fillable = [
        'tenant_id', 'title', 'title_en', 'nav_label', 'slug', 'description', 'description_en', 'is_visible', 'sort_order',
    ];

    protected $casts = ['is_visible' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $group) {
            if (empty($group->slug)) {
                $group->slug = Str::slug($group->title);
            }
            if (empty($group->nav_label)) {
                $group->nav_label = $group->title;
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return HasMany<Page, $this> */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class)->orderBy('sort_order');
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->description,
        );
    }
}
