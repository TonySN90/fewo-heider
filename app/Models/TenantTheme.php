<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string|null $color_primary
 * @property string|null $color_primary_dark
 * @property string|null $color_secondary
 * @property string|null $color_bg
 * @property string|null $color_bg_alt
 * @property string|null $color_border
 * @property string|null $color_footer_top
 * @property string|null $color_footer_bot
 * @property string|null $dark_color_primary
 * @property string|null $dark_color_primary_dark
 * @property string|null $dark_color_secondary
 * @property string|null $dark_color_bg
 * @property string|null $dark_color_bg_alt
 * @property string|null $dark_color_border
 * @property string|null $dark_color_footer_top
 * @property string|null $dark_color_footer_bot
 */
class TenantTheme extends Model
{
    protected $fillable = [
        'tenant_id',
        'color_primary',
        'color_primary_dark',
        'color_secondary',
        'color_bg',
        'color_bg_alt',
        'color_border',
        'color_footer_top',
        'color_footer_bot',
        'dark_color_primary',
        'dark_color_primary_dark',
        'dark_color_secondary',
        'dark_color_bg',
        'dark_color_bg_alt',
        'dark_color_border',
        'dark_color_footer_top',
        'dark_color_footer_bot',
    ];

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
