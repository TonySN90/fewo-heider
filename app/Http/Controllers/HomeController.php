<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
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

        $heroSection      = $activeTemplate?->getSection('hero');
        $aboutUsSection   = $activeTemplate?->getSection('ueber-uns');
        $amenitiesSection = $activeTemplate?->getSection('ausstattung');
        $gallerySection   = $activeTemplate?->getSection('galerie');

        $galleryImages = $gallerySection
            ? GalleryImage::where('template_section_id', $gallerySection->id)->orderBy('sort_order')->get()
            : collect();

        return view('home', compact('activeTemplate', 'visibleSections', 'heroSection', 'aboutUsSection', 'amenitiesSection', 'gallerySection', 'galleryImages'));
    }
}
