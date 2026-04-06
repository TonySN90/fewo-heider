<?php

if (! function_exists('current_tenant')) {
    function current_tenant(): ?\App\Models\Tenant
    {
        return app()->bound('currentTenant') ? app('currentTenant') : null;
    }
}
