<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use Illuminate\View\View;
use League\CommonMark\CommonMarkConverter;

class PublicLegalController extends Controller
{
    private CommonMarkConverter $markdown;

    public function __construct()
    {
        $this->markdown = new CommonMarkConverter([
            'html_input'         => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    public function impressum(): View
    {
        $page    = LegalPage::firstWhere('type', 'impressum');
        $content = $page?->content ? $this->markdown->convert($page->content)->getContent() : null;

        return view('impressum', compact('content'));
    }

    public function datenschutz(): View
    {
        $page    = LegalPage::firstWhere('type', 'datenschutz');
        $content = $page?->content ? $this->markdown->convert($page->content)->getContent() : null;

        return view('datenschutz', compact('content'));
    }
}
