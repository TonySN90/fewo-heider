<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\PageGroup;
use App\Models\Template;
use App\Models\TemplateSection;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const ALL_SECTIONS = [
        'hero', 'about', 'amenities', 'gallery', 'pricing', 'arrival', 'contact',
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

        // Kein Tenant → Plattform-Landingpage
        if ($tenant === null) {
            return view('landing');
        }

        $activeTemplate = $tenant !== null && $tenant->template_id !== null
            ? Template::find($tenant->template_id)
            : null;

        if ($activeTemplate !== null && $tenant !== null) {
            /** @var Collection<int, TemplateSection> $sections */
            $sections = $activeTemplate->sectionsForTenant($tenant->id)
                ->with('content', 'galleryImages')
                ->get();

            $visibleSections = $sections->where('is_visible', true)->pluck('section_key')->toArray();
            $orderedSections  = $sections->where('is_visible', true)->values();
            $headerSection = $sections->firstWhere('section_key', 'header');
            $heroSection = $sections->firstWhere('section_key', 'hero');
            $aboutSection = $sections->firstWhere('section_key', 'about');
            $amenitiesSection = $sections->firstWhere('section_key', 'amenities');
            $gallerySection = $sections->firstWhere('section_key', 'gallery');
            $arrivalSection = $sections->firstWhere('section_key', 'arrival');
            $contactSection = $sections->firstWhere('section_key', 'contact');
            $footerSection  = $sections->firstWhere('section_key', 'footer');
        } elseif ($activeTemplate !== null) {
            $activeTemplate->load('sections.content');
            $visibleSections = $activeTemplate->sections->where('is_visible', true)->pluck('section_key')->toArray();
            $orderedSections  = $activeTemplate->sections->where('is_visible', true)->values();
            $headerSection = $activeTemplate->getSection('header');
            $heroSection = $activeTemplate->getSection('hero');
            $aboutSection = $activeTemplate->getSection('about');
            $amenitiesSection = $activeTemplate->getSection('amenities');
            $gallerySection = $activeTemplate->getSection('gallery');
            $arrivalSection = $activeTemplate->getSection('arrival');
            $contactSection = $activeTemplate->getSection('contact');
            $footerSection  = $activeTemplate->getSection('footer');
        } else {
            $visibleSections = self::ALL_SECTIONS;
            $orderedSections  = collect(array_map(fn($k) => (object)['section_key' => $k], self::ALL_SECTIONS));
            $headerSection = $heroSection = $aboutSection = $amenitiesSection = $gallerySection = $arrivalSection = $contactSection = $footerSection = null;
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

        return view('home', compact('activeTemplate', 'visibleSections', 'orderedSections', 'headerSection', 'heroSection', 'aboutSection', 'amenitiesSection', 'gallerySection', 'galleryImages', 'arrivalSection', 'contactSection', 'footerSection', 'pageGroups'));
    }
}
