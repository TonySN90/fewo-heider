<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $available = array_keys(config('app.available_locales', ['de' => 'Deutsch']));
        $locale = $request->cookie('locale', $available[0]);
        $locale = in_array($locale, $available) ? $locale : $available[0];
        App::setLocale($locale);

        return $next($request);
    }
}
