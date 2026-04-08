<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateSectionContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateSectionController extends Controller
{
    public function edit(Template $template, string $sectionKey): View
    {
        $section = $template->findSection($sectionKey);
        $section->load('content', 'galleryImages');

        return view('admin.template-section-edit', compact('template', 'section'));
    }

    public function update(Request $request, Template $template, string $sectionKey): RedirectResponse
    {
        $section = $template->findSection($sectionKey);

        $fields = $request->input('fields', []);

        foreach ($fields as $key => $value) {
            TemplateSectionContent::updateOrCreate(
                ['template_section_id' => $section->id, 'field_key' => $key],
                ['value' => $value]
            );
        }

        return redirect()
            ->route('admin.templates')
            ->with('success', 'Sektion wurde gespeichert.');
    }
}
