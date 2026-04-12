<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantSEOMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($tenant = current_tenant()) {
            config([
                'seo.site_name' => $tenant->name,
                'seo.title.infer_title_from_url' => false,
            ]);
        }

        return $next($request);
    }
}
