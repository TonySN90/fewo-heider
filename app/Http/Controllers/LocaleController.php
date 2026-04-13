<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function set(Request $request, string $locale): RedirectResponse
    {
        $available = array_keys(config('app.available_locales', ['de' => 'Deutsch']));
        $locale = in_array($locale, $available) ? $locale : $available[0];

        return redirect()
            ->back(fallback: '/')
            ->withCookie(cookie()->forever('locale', $locale, '/', null, false, false));
    }
}
