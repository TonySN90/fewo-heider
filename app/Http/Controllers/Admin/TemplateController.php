<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateController extends Controller
{
    private const SECTION_LABELS = [
        'hero'        => 'Hero (Hauptbild)',
        'ueber-uns'   => 'Die Wohnung',
        'ausstattung' => 'Ausstattung',
        'galerie'     => 'Galerie',
        'preise'      => 'Preise & Verfügbarkeit',
        'anreise'     => 'Anreise / Karte',
        'kontakt'     => 'Kontakt & Anfrage',
    ];

    // Sections that have a dedicated edit page
    private const EDITABLE_SECTIONS = ['hero', 'ueber-uns', 'ausstattung'];

    public function index(): View
    {
        $templates = Template::with(['sections' => fn ($q) => $q->orderBy('sort_order')])->get();

        return view('admin.templates', [
            'templates'       => $templates,
            'sectionLabels'   => self::SECTION_LABELS,
            'editableSections' => self::EDITABLE_SECTIONS,
        ]);
    }

    public function update(Request $request, Template $template): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $template->update(['name' => $data['name']]);

        return back()->with('success', 'Template wurde umbenannt.');
    }

    public function activate(Template $template): RedirectResponse
    {
        $template->activate();

        return back()->with('success', 'Template "' . $template->name . '" ist jetzt aktiv.');
    }

    public function updateSections(Request $request, Template $template): RedirectResponse
    {
        $data = $request->validate([
            'sections'   => ['required', 'array'],
            'sections.*' => ['boolean'],
        ]);

        foreach ($data['sections'] as $key => $visible) {
            $template->sections()->where('section_key', $key)
                ->update(['is_visible' => (bool) $visible]);
        }

        return back()->with('success', 'Sektionen wurden aktualisiert.');
    }
}
