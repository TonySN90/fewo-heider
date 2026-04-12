<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateController extends Controller
{
    private const SECTION_LABELS = [
        'header'    => 'Header / Navigation',
        'hero'      => 'Hero (Hauptbild)',
        'about'     => 'Die Wohnung',
        'amenities' => 'Ausstattung',
        'gallery'   => 'Galerie',
        'pricing'   => 'Preise & Verfügbarkeit',
        'arrival'   => 'Anreise / Karte',
        'contact'   => 'Kontakt & Anfrage',
        'footer'    => 'Footer',
    ];

    private const EDITABLE_SECTIONS = ['header', 'hero', 'about', 'amenities', 'gallery', 'arrival', 'contact', 'footer'];

    public function index(): View
    {
        $templates = Template::with(['sections' => fn ($q) => $q->whereNull('tenant_id')->orderBy('sort_order')])->get();

        return view('admin.templates', [
            'templates' => $templates,
            'sectionLabels' => self::SECTION_LABELS,
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

        return back()->with('success', 'Template "'.$template->name.'" ist jetzt aktiv.');
    }

    public function updateSections(Request $request, Template $template): RedirectResponse
    {
        $data = $request->validate([
            'sections' => ['required', 'array'],
            'sections.*' => ['boolean'],
        ]);

        foreach ($data['sections'] as $key => $visible) {
            $isVisible = (bool) $visible;

            // Globale Mustersektion aktualisieren
            $template->sections()->where('section_key', $key)
                ->update(['is_visible' => $isVisible]);

            // Beim Deaktivieren: alle Tenant-Kopien ebenfalls sperren
            // Beim Aktivieren: Tenant-Kopien bleiben unberührt – der Client schaltet selbst ein
            if (! $isVisible) {
                TemplateSection::where('template_id', $template->id)
                    ->where('section_key', $key)
                    ->whereNotNull('tenant_id')
                    ->update(['is_visible' => false]);
            }
        }

        return back()->with('success', 'Sektionen wurden aktualisiert.');
    }
}
