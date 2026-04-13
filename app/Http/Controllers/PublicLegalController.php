<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use Illuminate\View\View;

class PublicLegalController extends Controller
{
    public function impressum(): View
    {
        $content = LegalPage::firstWhere('type', 'impressum')?->content;

        return view('impressum', compact('content'));
    }

    public function datenschutz(): View
    {
        $content = LegalPage::firstWhere('type', 'datenschutz')?->content;

        return view('datenschutz', compact('content'));
    }
}
