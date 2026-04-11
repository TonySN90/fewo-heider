<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property string|null $domain
 * @property int|null $template_id
 * @property bool $is_active
 * @property-read Template|null $template
 */
class Tenant extends Model
{
    protected $fillable = ['name', 'slug', 'domain', 'template_id', 'is_active'];

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
}
