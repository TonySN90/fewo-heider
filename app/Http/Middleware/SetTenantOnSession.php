<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetTenantOnSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $tenant = current_tenant();

        if ($tenant && $request->hasSession()) {
            DB::table('sessions')
                ->where('id', $request->session()->getId())
                ->whereNull('tenant_id')
                ->update(['tenant_id' => $tenant->id]);
        }

        return $response;
    }
}
