<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageGroup;
use App\Models\TemplateSectionContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageStructureController extends Controller
{
    private const SECTION_LABELS = [
        'hero' => 'Hero (Hauptbild)',
        'ueber-uns' => 'Die Wohnung',
        'ausstattung' => 'Ausstattung',
        'galerie' => 'Galerie',
        'preise' => 'Preise & Verfügbarkeit',
        'anreise' => 'Anreise / Karte',
        'kontakt' => 'Kontakt & Anfrage',
    ];

    private const EDITABLE_SECTIONS = ['hero', 'ueber-uns', 'ausstattung', 'galerie'];

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

        foreach ($request->input('fields', []) as $key => $value) {
            TemplateSectionContent::updateOrCreate(
                ['template_section_id' => $section->id, 'field_key' => $key],
                ['value' => $value]
            );
        }

        return redirect()
            ->route('admin.page-structure')
            ->with('success', 'Sektion wurde gespeichert.');
    }
}
