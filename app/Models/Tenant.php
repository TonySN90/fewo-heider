<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

/**
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property string|null $domain
 * @property int|null $template_id
 * @property bool $is_active
 * @property string|null $seo_description
 * @property string|null $seo_og_image
 * @property-read Template|null $template
 */
class Tenant extends Model
{
    use HasSEO;

    protected $fillable = ['name', 'slug', 'domain', 'template_id', 'is_active', 'seo_description', 'seo_og_image'];

    protected $casts = ['is_active' => 'boolean'];

    /** @return BelongsTo<Template, $this> */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /** @return BelongsToMany<User, $this> */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /** @return HasOne<TenantTheme, $this> */
    public function theme(): HasOne
    {
        return $this->hasOne(TenantTheme::class);
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->name,
            description: $this->seo_description ?? $this->name . ' – Jetzt anfragen!',
            image: $this->seo_og_image ? Storage::url($this->seo_og_image) : null,
        );
    }
}
