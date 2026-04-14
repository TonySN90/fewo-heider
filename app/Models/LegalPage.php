<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $type  'datenschutz'|'impressum'
 * @property string|null $content
 * @property-read Tenant $tenant
 */
class LegalPage extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'type',
        'locale',
        'content',
    ];
}
