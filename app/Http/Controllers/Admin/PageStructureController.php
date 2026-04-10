<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageGroup;
use App\Models\TemplateSectionContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageStructureController extends Controller
{
    private const SECTION_LABELS = [
        'hero'      => 'Hero (Hauptbild)',
        'about'     => 'Die Wohnung',
        'amenities' => 'Ausstattung',
        'gallery'   => 'Galerie',
        'pricing'   => 'Preise & Verfügbarkeit',
        'arrival'   => 'Anreise / Karte',
        'contact'   => 'Kontakt & Anfrage',
    ];

    private const EDITABLE_SECTIONS = ['hero', 'about', 'amenities', 'gallery', 'arrival', 'contact'];

    public function index(): View
    {
        $tenant = current_tenant();
        $template = $tenant?->template;

        abort_unless($tenant && $template, 404, 'Kein Template zugeordnet.');

        $sections = $template->sectionsForTenant($tenant->id)->get();
        $groups = PageGroup::where('tenant_id', $tenant->id)
            ->orderBy('sort_order')
            ->with(['pages' => fn ($q) => $q->orderBy('sort_order')->with(['entries' => fn ($eq) => $eq->orderBy('sort_order')->limit(1)->with('blocks')])])
            ->get();

        return view('admin.page-structure', [
            'template' => $template,
            'sections' => $sections,
            'sectionLabels' => self::SECTION_LABELS,
            'editableSections' => self::EDITABLE_SECTIONS,
            'groups' => $groups,
        ]);
    }

    public function updateSections(Request $request): RedirectResponse
    {
        $tenant = current_tenant();
        $template = $tenant?->template;

        abort_unless($tenant && $template, 404);

        $data = $request->validate([
            'sections' => ['required', 'array'],
            'sections.*' => ['boolean'],
        ]);

        foreach ($data['sections'] as $key => $visible) {
            $template->sectionsForTenant($tenant->id)
                ->where('section_key', $key)
                ->update(['is_visible' => (bool) $visible]);
        }

        return back()->with('success', 'Seitenstruktur gespeichert.');
    }

    public function edit(string $sectionKey): View
    {
        $tenant = current_tenant();
        $template = $tenant?->template;

        abort_unless($tenant && $template, 404);

        $section = $template->findTenantSection($sectionKey, $tenant->id);
        $section->load('content', 'galleryImages');

        return view('admin.page-structure-section-edit', compact('template', 'section', 'sectionKey'));
    }

    public function update(Request $request, string $sectionKey): RedirectResponse
    {
        $tenant = current_tenant();
        $template = $tenant?->template;

        abort_unless($tenant && $template, 404);

        $section = $template->findTenantSection($sectionKey, $tenant->id);

        $fields = $request->input('fields', []);

        // Hero-Bild: löschen
        if (isset($fields['cover_image_delete']) && $fields['cover_image_delete'] === '1') {
            $existing = TemplateSectionContent::where('template_section_id', $section->id)
                ->where('field_key', 'cover_image')->first();
            if ($existing) {
                Storage::disk('public')->delete($existing->value);
                $existing->delete();
            }
            unset($fields['cover_image_delete']);
        }

        // Hero-Bild: hochladen
        if ($request->hasFile('hero_image')) {
            $request->validate([
                'hero_image' => ['file', 'image', 'max:20480', 'mimes:jpg,jpeg,png,webp'],
            ]);
            $file = $request->file('hero_image');
            $filename = (string) Str::uuid().'.webp';

            [$width, $height] = getimagesize($file->getRealPath());
            if ($height > $width) {
                return back()->withErrors(['hero_image' => 'Bitte kein Hochkantbild hochladen. Das Bild muss breiter als hoch sein.'])->withInput();
            }

            $image = new \Imagick($file->getRealPath());
            $image->setImageFormat('webp');
            if ($image->getImageWidth() > 1800) {
                $image->resizeImage(1800, 0, \Imagick::FILTER_LANCZOS, 1);
            }
            $image->setImageCompressionQuality(85);
            Storage::disk('public')->makeDirectory('hero');
            $image->writeImage(Storage::disk('public')->path('hero/'.$filename));
            $image->destroy();

            $path = 'hero/'.$filename;

            // Altes Bild löschen
            $existing = TemplateSectionContent::where('template_section_id', $section->id)
                ->where('field_key', 'cover_image')->first();
            if ($existing) {
                Storage::disk('public')->delete($existing->value);
            }

            TemplateSectionContent::updateOrCreate(
                ['template_section_id' => $section->id, 'field_key' => 'cover_image'],
                ['value' => $path]
            );
        }

        foreach ($fields as $key => $value) {
            if ($value === '' || $value === null) {
                TemplateSectionContent::where('template_section_id', $section->id)
                    ->where('field_key', $key)
                    ->delete();
            } else {
                TemplateSectionContent::updateOrCreate(
                    ['template_section_id' => $section->id, 'field_key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()
            ->route('admin.page-structure.edit', $sectionKey)
            ->with('success', 'Sektion wurde gespeichert.');
    }
}
