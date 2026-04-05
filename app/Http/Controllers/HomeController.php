<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const ALL_SECTIONS = [
        'hero', 'ueber-uns', 'ausstattung', 'galerie', 'preise', 'anreise', 'kontakt',
    ];

    public function index(): View
    {
        $activeTemplate = Template::active();

        if ($activeTemplate) {
            $activeTemplate->load('sections.content');
            $visibleSections = $activeTemplate->sections->where('is_visible', true)->pluck('section_key')->toArray();
        } else {
            $visibleSections = self::ALL_SECTIONS;
        }

        $heroSection     = $activeTemplate?->sections->firstWhere('section_key', 'hero');
        $aboutUsSection = $activeTemplate?->sections->firstWhere('section_key', 'ueber-uns');

        return view('home', compact('activeTemplate', 'visibleSections', 'heroSection', 'aboutUsSection'));
    }
}
