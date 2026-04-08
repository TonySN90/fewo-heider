<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            if (! $model->tenant_id) {
                $tenant = current_tenant();
                if ($tenant) {
                    $model->tenant_id = $tenant->id;
                }
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenant = current_tenant();
            if ($tenant) {
                $builder->where($builder->getModel()->getTable().'.tenant_id', $tenant->id);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
