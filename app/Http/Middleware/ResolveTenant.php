<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = null;
        $user   = $request->user();

        // 1. Super-Admin wechselt per Session in eine Instanz
        if ($user?->hasRole('super-admin') && $request->session()->has('admin_tenant_id')) {
            $tenant = Tenant::find($request->session()->get('admin_tenant_id'));
        }

        // 2. Client hat eine Instanz per Session gewählt (muss zur eigenen gehören)
        if (! $tenant && $user && ! $user->hasRole('super-admin') && $request->session()->has('current_tenant_id')) {
            $tenantId = $request->session()->get('current_tenant_id');
            $tenant   = $user->tenants()->where('tenants.id', $tenantId)->first();
        }

        // 3. Domain-Lookup
        if (! $tenant) {
            $tenant = Tenant::where('domain', $request->getHost())
                ->where('is_active', true)
                ->first();
        }

        if ($tenant) {
            app()->instance('currentTenant', $tenant);
            return $next($request);
        }

        // Super-Admin auf eigenem Domain ohne gesetzten Kontext → Tenant-Verwaltungsbereich
        if ($user?->hasRole('super-admin') && $request->is('admin*')) {
            return $next($request);
        }

        // Öffentliche Route ohne passende Domain
        if (! $request->is('admin*')) {
            abort(404);
        }

        // Admin-Route, kein Tenant, kein Super-Admin
        abort(403, 'Keine Instanz zugeordnet.');
    }
}
