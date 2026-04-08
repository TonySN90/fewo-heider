<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\Template;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    // ── Tenant-Kontext (Seitenstruktur) ─────────────────────────────────────

    public function storeForTenant(Request $request, string $sectionKey): RedirectResponse
    {
        $tenant = current_tenant();
        $template = $tenant?->template;
        abort_unless($tenant && $template, 404);

        $section = $template->findTenantSection($sectionKey, $tenant->id);

        $request->validate([
            'images' => ['required', 'array', 'max:20'],
            'images.*' => ['required', 'file', 'image', 'max:5120', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $nextOrder = GalleryImage::where('template_section_id', $section->id)->max('sort_order') ?? 0;

        foreach ($request->file('images') as $file) {
            $filename = (string) Str::uuid().'.'.$file->getClientOriginalExtension();
            $file->storeAs('gallery', $filename, 'public');
            GalleryImage::create([
                'template_section_id' => $section->id,
                'filename' => $filename,
                'caption' => null,
                'sort_order' => ++$nextOrder,
            ]);
        }

        return redirect()
            ->route('admin.page-structure.edit', $sectionKey)
            ->with('success', count($request->file('images')).' Bild(er) wurden hochgeladen.');
    }

    public function updateForTenant(Request $request, string $sectionKey, GalleryImage $image): RedirectResponse
    {
        $tenant = current_tenant();
        $template = $tenant?->template;
        abort_unless($tenant && $template, 404);

        $section = $template->findTenantSection($sectionKey, $tenant->id);
        abort_if($image->template_section_id !== $section->id, 403);

        $image->update($request->validate([
            'caption' => ['nullable', 'string', 'max:200'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]));

        return redirect()
            ->route('admin.page-structure.edit', $sectionKey)
            ->with('success', 'Bild wurde aktualisiert.');
    }

    public function destroyForTenant(string $sectionKey, GalleryImage $image): RedirectResponse
    {
        $tenant = current_tenant();
        $template = $tenant?->template;
        abort_unless($tenant && $template, 404);

        $section = $template->findTenantSection($sectionKey, $tenant->id);
        abort_if($image->template_section_id !== $section->id, 403);

        Storage::disk('public')->delete('gallery/'.$image->filename);
        $image->delete();

        return redirect()
            ->route('admin.page-structure.edit', $sectionKey)
            ->with('success', 'Bild wurde gelöscht.');
    }

    // ── Globale Template-Verwaltung (Super-Admin) ────────────────────────────

    public function store(Request $request, Template $template, string $sectionKey): RedirectResponse
    {
        $section = $template->findSection($sectionKey);

        $request->validate([
            'images' => ['required', 'array', 'max:20'],
            'images.*' => ['required', 'file', 'image', 'max:5120', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $nextOrder = GalleryImage::where('template_section_id', $section->id)->max('sort_order') ?? 0;

        foreach ($request->file('images') as $file) {
            $filename = (string) Str::uuid().'.'.$file->getClientOriginalExtension();
            $file->storeAs('gallery', $filename, 'public');

            GalleryImage::create([
                'template_section_id' => $section->id,
                'filename' => $filename,
                'caption' => null,
                'sort_order' => ++$nextOrder,
            ]);
        }

        return redirect()
            ->route('admin.templates.sections.edit', [$template, $sectionKey])
            ->with('success', count($request->file('images')).' Bild(er) wurden hochgeladen.');
    }

    public function update(Request $request, Template $template, string $sectionKey, GalleryImage $image): RedirectResponse
    {
        $section = $template->findSection($sectionKey);
        abort_if($image->template_section_id !== $section->id, 403);

        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:200'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $image->update($data);

        return redirect()
            ->route('admin.templates.sections.edit', [$template, $sectionKey])
            ->with('success', 'Bild wurde aktualisiert.');
    }

    public function destroy(Template $template, string $sectionKey, GalleryImage $image): RedirectResponse
    {
        $section = $template->findSection($sectionKey);
        abort_if($image->template_section_id !== $section->id, 403);

        Storage::disk('public')->delete('gallery/'.$image->filename);
        $image->delete();

        return redirect()
            ->route('admin.templates.sections.edit', [$template, $sectionKey])
            ->with('success', 'Bild wurde gelöscht.');
    }
}
