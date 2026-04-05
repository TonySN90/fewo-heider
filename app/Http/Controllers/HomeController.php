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

        $visibleSections = $activeTemplate
            ? $activeTemplate->sections->where('is_visible', true)->pluck('section_key')->toArray()
            : self::ALL_SECTIONS;

        return view('home', compact('activeTemplate', 'visibleSections'));
    }
}
