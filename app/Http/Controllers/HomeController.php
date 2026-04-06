<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\PageGroup;
use App\Models\Tenant;
use App\Models\Template;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const ALL_SECTIONS = [
        'hero', 'ueber-uns', 'ausstattung', 'galerie', 'preise', 'anreise', 'kontakt',
    ];

    public function preview(string $tenantSlug): View
    {
        $tenant = Tenant::where('slug', $tenantSlug)->where('is_active', true)->firstOrFail();
        app()->instance('currentTenant', $tenant);

        return $this->index();
    }

    public function index(): View
    {
        $tenant = current_tenant();

        $activeTemplate = $tenant !== null && $tenant->template_id !== null
            ? Template::find($tenant->template_id)
            : null;

        if ($activeTemplate !== null && $tenant !== null) {
            /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\TemplateSection> $sections */
            $sections = $activeTemplate->sectionsForTenant($tenant->id)
                ->with('content', 'galleryImages')
                ->get();

            $visibleSections  = $sections->where('is_visible', true)->pluck('section_key')->toArray();
            $heroSection      = $sections->firstWhere('section_key', 'hero');
            $aboutUsSection   = $sections->firstWhere('section_key', 'ueber-uns');
            $amenitiesSection = $sections->firstWhere('section_key', 'ausstattung');
            $gallerySection   = $sections->firstWhere('section_key', 'galerie');
        } elseif ($activeTemplate !== null) {
            $activeTemplate->load('sections.content');
            $visibleSections  = $activeTemplate->sections->where('is_visible', true)->pluck('section_key')->toArray();
            $heroSection      = $activeTemplate->getSection('hero');
            $aboutUsSection   = $activeTemplate->getSection('ueber-uns');
            $amenitiesSection = $activeTemplate->getSection('ausstattung');
            $gallerySection   = $activeTemplate->getSection('galerie');
        } else {
            $visibleSections  = self::ALL_SECTIONS;
            $heroSection = $aboutUsSection = $amenitiesSection = $gallerySection = null;
        }

        $galleryImages = $gallerySection
            ? GalleryImage::where('template_section_id', $gallerySection->id)->orderBy('sort_order')->get()
            : collect();

        $pageGroups = $tenant
            ? PageGroup::where('tenant_id', $tenant->id)
                ->where('is_visible', true)
                ->orderBy('sort_order')
                ->get()
            : collect();

        return view('home', compact('activeTemplate', 'visibleSections', 'heroSection', 'aboutUsSection', 'amenitiesSection', 'gallerySection', 'galleryImages', 'pageGroups'));
    }
}
