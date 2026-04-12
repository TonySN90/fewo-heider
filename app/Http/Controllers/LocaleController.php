<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function set(Request $request, string $locale): RedirectResponse
    {
        $locale = in_array($locale, ['de', 'en']) ? $locale : 'de';

        return redirect()
            ->back(fallback: '/')
            ->withCookie(cookie()->forever('locale', $locale, '/', null, false, false));
    }
}
