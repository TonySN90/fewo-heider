<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageEntry;
use App\Models\PageEntryBlock;
use App\Models\PageGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    // ── Gruppen ───────────────────────────────────────────────────────────────

    public function storeGroup(Request $request): RedirectResponse
    {
        $tenant = current_tenant();
        abort_if($tenant === null, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'nav_label' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_visible' => ['boolean'],
        ]);

        $slug = Str::slug($data['title']);
        $base = $slug;
        $i = 1;
        while (PageGroup::where('tenant_id', $tenant->id)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        $nextOrder = PageGroup::where('tenant_id', $tenant->id)->max('sort_order') ?? 0;

        PageGroup::create([
            'tenant_id' => $tenant->id,
            'title' => $data['title'],
            'nav_label' => $data['nav_label'] ?: $data['title'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'is_visible' => $request->boolean('is_visible', true),
            'sort_order' => $nextOrder + 1,
        ]);

        return redirect()->route('admin.page-structure')
            ->with('success', 'Gruppe „'.$data['title'].'" wurde angelegt.');
    }

    public function updateGroup(Request $request, PageGroup $group): JsonResponse|RedirectResponse
    {
        $this->authorizeGroup($group);

        $data = $request->validate([
            'title'          => ['required', 'string', 'max:150'],
            'title_en'       => ['nullable', 'string', 'max:150'],
            'nav_label'      => ['nullable', 'string', 'max:150'],
            'description'    => ['nullable', 'string', 'max:500'],
            'description_en' => ['nullable', 'string', 'max:500'],
            'is_visible'     => ['nullable', 'boolean'],
        ]);

        $newSlug = Str::slug($data['title']);
        if ($newSlug !== $group->slug) {
            $base = $newSlug;
            $i = 1;
            while (PageGroup::where('tenant_id', $group->tenant_id)->where('slug', $newSlug)->where('id', '!=', $group->id)->exists()) {
                $newSlug = $base.'-'.$i++;
            }
        }

        $update = [
            'title' => $data['title'],
            'slug'  => $newSlug,
        ];
        if ($request->has('title_en'))       $update['title_en']       = $data['title_en'] ?? null;
        if ($request->has('nav_label'))      $update['nav_label']      = ($data['nav_label'] ?? null) ?: $data['title'];
        if ($request->has('description'))    $update['description']    = $data['description'] ?? null;
        if ($request->has('description_en')) $update['description_en'] = $data['description_en'] ?? null;
        if ($request->has('is_visible'))     $update['is_visible']     = $request->boolean('is_visible');

        $group->update($update);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.page-structure')
            ->with('success', 'Gruppe gespeichert.');
    }

    public function destroyGroup(PageGroup $group): RedirectResponse
    {
        $this->authorizeGroup($group);
        $group->delete();

        return redirect()->route('admin.page-structure')
            ->with('success', 'Gruppe gelöscht.');
    }

    public function updateGroupSeo(Request $request, PageGroup $group): JsonResponse
    {
        $this->authorizeGroup($group);

        $data = $request->validate([
            'seo_title'       => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:160'],
        ]);

        $group->seo()->update([
            'title'       => $data['seo_title'] ?: null,
            'description' => $data['seo_description'] ?: null,
        ]);

        return response()->json(['ok' => true]);
    }

    // ── Kategorien ───────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $tenant = current_tenant();
        abort_if($tenant === null, 403);

        $data = $request->validate([
            'page_group_id' => ['required', 'integer', 'exists:page_groups,id'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'is_visible' => ['boolean'],
            'layout' => ['nullable', 'in:cards,feature,route,hero-feature'],
        ]);

        $slug = Str::slug($data['title']);
        $base = $slug;
        $i = 1;
        while (Page::where('tenant_id', $tenant->id)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        $nextOrder = Page::where('tenant_id', $tenant->id)->max('sort_order') ?? 0;

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('pages', 'public');
        }

        Page::create([
            'tenant_id' => $tenant->id,
            'page_group_id' => $data['page_group_id'],
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'cover_image' => $coverPath,
            'is_visible' => $request->boolean('is_visible', true),
            'sort_order' => $nextOrder + 1,
            'layout' => $data['layout'] ?? 'cards',
        ]);

        return redirect()->route('admin.page-structure')
            ->with('success', 'Kategorie „'.$data['title'].'" wurde angelegt.');
    }

    public function update(Request $request, Page $page): JsonResponse|RedirectResponse
    {
        $this->authorizePage($page);

        $data = $request->validate([
            'title'            => ['required', 'string', 'max:150'],
            'title_en'         => ['nullable', 'string', 'max:150'],
            'description'      => ['nullable', 'string', 'max:500'],
            'description_en'   => ['nullable', 'string', 'max:500'],
            'cover_image'      => ['nullable', 'image', 'max:4096'],
            'is_visible'       => ['nullable', 'boolean'],
            'layout'           => ['nullable', 'in:cards,feature,route,hero-feature'],
            'intro_heading'    => ['nullable', 'string', 'max:200'],
            'intro_text'       => ['nullable', 'string', 'max:2000'],
            'intro_heading_en' => ['nullable', 'string', 'max:200'],
            'intro_text_en'    => ['nullable', 'string', 'max:2000'],
        ]);

        $update = ['title' => $data['title']];
        if ($request->has('title_en'))       $update['title_en']       = $data['title_en'] ?? null;
        if ($request->has('description'))    $update['description']    = $data['description'] ?? null;
        if ($request->has('description_en')) $update['description_en'] = $data['description_en'] ?? null;
        if ($request->has('layout'))         $update['layout']         = $data['layout'] ?? $page->layout;
        if ($request->has('is_visible'))     $update['is_visible']     = $request->boolean('is_visible');
        if ($request->hasFile('cover_image')) {
            $update['cover_image'] = $request->file('cover_image')->store('pages', 'public');
        }

        $page->update($update);

        // Intro-Blöcke nur updaten wenn die Felder gesendet wurden
        if ($request->has('intro_heading') || $request->has('intro_text') || $request->has('intro_heading_en') || $request->has('intro_text_en')) {
            $introEntry = $page->entries()->orderBy('sort_order')->first();
            if ($introEntry) {
                if ($request->has('intro_heading')) {
                    $headingBlock = $introEntry->blocks()->where('type', 'heading')->first();
                    if ($headingBlock) {
                        $headingBlock->update(['content' => $data['intro_heading'] ?? '']);
                    } elseif (!empty($data['intro_heading'])) {
                        $introEntry->blocks()->create(['type' => 'heading', 'content' => $data['intro_heading'], 'sort_order' => 0]);
                    }
                }
                if ($request->has('intro_text')) {
                    $textBlock = $introEntry->blocks()->where('type', 'text')->first();
                    if ($textBlock) {
                        $textBlock->update(['content' => $data['intro_text'] ?? '']);
                    } elseif (!empty($data['intro_text'])) {
                        $introEntry->blocks()->create(['type' => 'text', 'content' => $data['intro_text'], 'sort_order' => 1]);
                    }
                }
                if ($request->has('intro_heading_en')) {
                    $headingEnBlock = $introEntry->blocks()->where('type', 'heading_en')->first();
                    if ($headingEnBlock) {
                        $headingEnBlock->update(['content' => $data['intro_heading_en'] ?? '']);
                    } elseif (!empty($data['intro_heading_en'])) {
                        $introEntry->blocks()->create(['type' => 'heading_en', 'content' => $data['intro_heading_en'], 'sort_order' => 2]);
                    }
                }
                if ($request->has('intro_text_en')) {
                    $textEnBlock = $introEntry->blocks()->where('type', 'text_en')->first();
                    if ($textEnBlock) {
                        $textEnBlock->update(['content' => $data['intro_text_en'] ?? '']);
                    } elseif (!empty($data['intro_text_en'])) {
                        $introEntry->blocks()->create(['type' => 'text_en', 'content' => $data['intro_text_en'], 'sort_order' => 3]);
                    }
                }
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.page-structure')
            ->with('success', 'Kategorie gespeichert.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $this->authorizePage($page);
        $page->delete();

        return redirect()->route('admin.page-structure')
            ->with('success', 'Kategorie gelöscht.');
    }

    public function updatePageSeo(Request $request, Page $page): JsonResponse
    {
        $this->authorizePage($page);

        $data = $request->validate([
            'seo_title'       => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:160'],
        ]);

        $page->seo()->update([
            'title'       => $data['seo_title'] ?: null,
            'description' => $data['seo_description'] ?: null,
        ]);

        return response()->json(['ok' => true]);
    }

    // ── Einträge ──────────────────────────────────────────────────────────────

    public function entries(Page $page): View
    {
        $this->authorizePage($page);
        $page->load('entries.blocks');

        return view('admin.page-entries', compact('page'));
    }

    public function storeEntry(Request $request, Page $page): RedirectResponse
    {
        $this->authorizePage($page);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $slug = Str::slug($data['title']);
        $base = $slug;
        $i = 1;
        while (PageEntry::where('page_id', $page->id)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        $nextOrder = PageEntry::where('page_id', $page->id)->max('sort_order') ?? 0;

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('pages', 'public');
        }

        $entry = PageEntry::create([
            'page_id' => $page->id,
            'title' => $data['title'],
            'slug' => $slug,
            'cover_image' => $coverPath,
            'sort_order' => $nextOrder + 1,
        ]);

        if ($page->layout === 'cards') {
            $placeholders = [
                ['type' => 'badge',  'content' => 'Leicht',          'color' => 'green', 'sort_order' => 1],
                ['type' => 'badge',  'content' => '5 km',            'color' => 'blue',  'sort_order' => 2],
                ['type' => 'text',   'content' => 'Kurze Beschreibung dieser Tour oder dieses Ortes.', 'color' => null, 'sort_order' => 3],
                ['type' => 'text',   'content' => "Highlights\n- Erster Punkt\n- Zweiter Punkt\n- Dritter Punkt", 'color' => null, 'sort_order' => 4],
            ];
            foreach ($placeholders as $p) {
                PageEntryBlock::create([
                    'page_entry_id' => $entry->id,
                    'type' => $p['type'],
                    'content' => $p['content'],
                    'color' => $p['color'],
                    'sort_order' => $p['sort_order'],
                ]);
            }
        }

        return redirect()->route('admin.pages.entry.edit', [$page, $entry])
            ->with('success', 'Eintrag angelegt.');
    }

    public function updateEntrySeo(Request $request, Page $page, PageEntry $entry): RedirectResponse
    {
        $this->authorizePage($page);
        abort_if($entry->page_id !== $page->id, 403);

        $data = $request->validate([
            'seo_title'       => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:160'],
        ]);

        $entry->seo()->update([
            'title'       => $data['seo_title'] ?: null,
            'description' => $data['seo_description'] ?: null,
        ]);

        return back()->with('success', 'SEO gespeichert.');
    }

    public function updateEntry(Request $request, Page $page, PageEntry $entry): RedirectResponse
    {
        $this->authorizePage($page);
        abort_if($entry->page_id !== $page->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'image_position' => ['nullable', 'in:left,right'],
        ]);

        $coverPath = $entry->cover_image;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('pages', 'public');
        }

        $entry->update([
            'title' => $data['title'],
            'cover_image' => $coverPath,
            'image_position' => $data['image_position'] ?? $entry->image_position,
        ]);

        return back()->with('success', 'Eintrag gespeichert.');
    }

    public function destroyEntry(Page $page, PageEntry $entry): RedirectResponse
    {
        $this->authorizePage($page);
        abort_if($entry->page_id !== $page->id, 403);
        $entry->delete();

        return redirect()->route('admin.pages.entries', $page)
            ->with('success', 'Eintrag gelöscht.');
    }

    public function editEntry(Page $page, PageEntry $entry): View
    {
        $this->authorizePage($page);
        abort_if($entry->page_id !== $page->id, 403);
        $entry->load('blocks');
        $page->load('group');

        return view('admin.page-entry-edit', compact('page', 'entry'));
    }

    // ── Blöcke ────────────────────────────────────────────────────────────────

    public function storeBlock(Request $request, Page $page, PageEntry $entry)
    {
        $this->authorizePage($page);
        abort_if($entry->page_id !== $page->id, 403);

        $data = $request->validate([
            'type' => ['required', 'in:text,heading,image,badge,info'],
            'content' => ['nullable', 'string', 'max:2000'],
            'color' => ['nullable', 'in:green,blue,orange,gray'],
            'icon' => ['nullable', 'string', 'max:100'],
        ]);

        $nextOrder = PageEntryBlock::where('page_entry_id', $entry->id)->max('sort_order') ?? 0;

        $block = PageEntryBlock::create([
            'page_entry_id' => $entry->id,
            'type' => $data['type'],
            'content' => $data['content'] ?? '',
            'color' => $data['color'] ?? null,
            'icon' => $data['icon'] ?? null,
            'sort_order' => $nextOrder + 1,
        ]);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id' => $block->id,
                'url' => route('admin.pages.blocks.update', [$page, $entry, $block]),
                'deleteUrl' => route('admin.pages.blocks.destroy', [$page, $entry, $block]),
            ]);
        }

        return back()->with('success', 'Block hinzugefügt.');
    }

    public function updateBlock(Request $request, Page $page, PageEntry $entry, PageEntryBlock $block)
    {
        $this->authorizePage($page);
        abort_if($block->page_entry_id !== $entry->id, 403);

        $data = $request->validate([
            'content' => ['nullable', 'string', 'max:2000'],
            'color' => ['nullable', 'in:green,blue,orange,gray'],
            'icon' => ['nullable', 'string', 'max:100'],
        ]);

        $block->update([
            'content' => $data['content'] ?? '',
            'color' => $data['color'] ?? null,
            'icon' => array_key_exists('icon', $data) ? $data['icon'] : $block->icon,
        ]);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Block gespeichert.');
    }

    public function destroyBlock(Request $request, Page $page, PageEntry $entry, PageEntryBlock $block)
    {
        $this->authorizePage($page);
        abort_if($block->page_entry_id !== $entry->id, 403);
        $block->delete();

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Block gelöscht.');
    }

    public function reorderBlocks(Request $request, Page $page, PageEntry $entry)
    {
        $this->authorizePage($page);
        abort_if($entry->page_id !== $page->id, 403);

        $ids = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']])['ids'];

        foreach ($ids as $order => $id) {
            PageEntryBlock::where('id', $id)
                ->where('page_entry_id', $entry->id)
                ->update(['sort_order' => $order + 1]);
        }

        return response()->json(['ok' => true]);
    }

    // ── Hilfsmethoden ─────────────────────────────────────────────────────────

    private function authorizePage(Page $page): void
    {
        $tenant = current_tenant();
        abort_unless($tenant && $page->tenant_id === $tenant->id, 403);
    }

    private function authorizeGroup(PageGroup $group): void
    {
        $tenant = current_tenant();
        abort_unless($tenant && $group->tenant_id === $tenant->id, 403);
    }
}
