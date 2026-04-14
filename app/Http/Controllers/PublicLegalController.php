<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use Illuminate\View\View;

class PublicLegalController extends Controller
{
    public function impressum(): View
    {
        $locale  = app()->getLocale();
        $content = LegalPage::where('type', 'impressum')->where('locale', $locale)->first()?->content;

        return view('impressum', compact('content'));
    }

    public function datenschutz(): View
    {
        $locale  = app()->getLocale();
        $content = LegalPage::where('type', 'datenschutz')->where('locale', $locale)->first()?->content;

        return view('datenschutz', compact('content'));
    }
}
