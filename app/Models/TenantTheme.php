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
 * @property string|null $color_bg_alt
 * @property string|null $color_border
 * @property string|null $color_footer_top
 * @property string|null $color_footer_bot
 */
class TenantTheme extends Model
{
    protected $fillable = [
        'tenant_id',
        'color_primary',
        'color_primary_dark',
        'color_secondary',
        'color_bg_alt',
        'color_border',
        'color_footer_top',
        'color_footer_bot',
    ];

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
