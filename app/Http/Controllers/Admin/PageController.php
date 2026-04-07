<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageEntry;
use App\Models\PageEntryBlock;
use App\Models\PageGroup;
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
        abort_unless($tenant, 403);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'nav_label'   => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_visible'  => ['boolean'],
        ]);

        $slug = Str::slug($data['title']);
        $base = $slug;
        $i    = 1;
        while (PageGroup::where('tenant_id', $tenant->id)->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $nextOrder = PageGroup::where('tenant_id', $tenant->id)->max('sort_order') ?? 0;

        PageGroup::create([
            'tenant_id'   => $tenant->id,
            'title'       => $data['title'],
            'nav_label'   => $data['nav_label'] ?: $data['title'],
            'slug'        => $slug,
            'description' => $data['description'] ?? null,
            'is_visible'  => $request->boolean('is_visible', true),
            'sort_order'  => $nextOrder + 1,
        ]);

        return redirect()->route('admin.page-structure')
            ->with('success', 'Gruppe „' . $data['title'] . '" wurde angelegt.');
    }

    public function updateGroup(Request $request, PageGroup $group): RedirectResponse
    {
        $this->authorizeGroup($group);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'nav_label'   => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_visible'  => ['boolean'],
        ]);

        $newSlug = Str::slug($data['title']);
        if ($newSlug !== $group->slug) {
            $base = $newSlug;
            $i    = 1;
            while (PageGroup::where('tenant_id', $group->tenant_id)->where('slug', $newSlug)->where('id', '!=', $group->id)->exists()) {
                $newSlug = $base . '-' . $i++;
            }
        }

        $group->update([
            'title'       => $data['title'],
            'slug'        => $newSlug,
            'nav_label'   => $data['nav_label'] ?: $data['title'],
            'description' => $data['description'] ?? null,
            'is_visible'  => $request->boolean('is_visible', true),
        ]);

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

    // ── Kategorien ───────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $tenant = current_tenant();
        abort_unless($tenant, 403);

        $data = $request->validate([
            'page_group_id' => ['required', 'integer', 'exists:page_groups,id'],
            'title'         => ['required', 'string', 'max:150'],
            'description'   => ['nullable', 'string', 'max:500'],
            'cover_image'   => ['nullable', 'image', 'max:4096'],
            'is_visible'    => ['boolean'],
            'layout'        => ['nullable', 'in:cards,place-list,feature,route,hero-feature'],
        ]);

        $slug = Str::slug($data['title']);
        $base = $slug;
        $i    = 1;
        while (Page::where('tenant_id', $tenant->id)->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $nextOrder = Page::where('tenant_id', $tenant->id)->max('sort_order') ?? 0;

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('pages', 'public');
        }

        Page::create([
            'tenant_id'     => $tenant->id,
            'page_group_id' => $data['page_group_id'],
            'title'         => $data['title'],
            'slug'          => $slug,
            'description'   => $data['description'] ?? null,
            'cover_image'   => $coverPath,
            'is_visible'    => $request->boolean('is_visible', true),
            'sort_order'    => $nextOrder + 1,
            'layout'        => $data['layout'] ?? 'cards',
        ]);

        return redirect()->route('admin.page-structure')
            ->with('success', 'Kategorie „' . $data['title'] . '" wurde angelegt.');
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $this->authorizePage($page);

        $data = $request->validate([
            'title'         => ['required', 'string', 'max:150'],
            'description'   => ['nullable', 'string', 'max:500'],
            'cover_image'   => ['nullable', 'image', 'max:4096'],
            'is_visible'    => ['boolean'],
            'layout'        => ['nullable', 'in:cards,place-list,feature,route,hero-feature'],
        ]);

        $coverPath = $page->cover_image;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('pages', 'public');
        }

        $page->update([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'cover_image' => $coverPath,
            'is_visible'  => $request->boolean('is_visible', true),
            'layout'      => $data['layout'] ?? $page->layout,
        ]);

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
            'title'       => ['required', 'string', 'max:200'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $slug = Str::slug($data['title']);
        $base = $slug;
        $i    = 1;
        while (PageEntry::where('page_id', $page->id)->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $nextOrder = PageEntry::where('page_id', $page->id)->max('sort_order') ?? 0;

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('pages', 'public');
        }

        $entry = PageEntry::create([
            'page_id'     => $page->id,
            'title'       => $data['title'],
            'slug'        => $slug,
            'cover_image' => $coverPath,
            'sort_order'  => $nextOrder + 1,
        ]);

        return redirect()->route('admin.pages.entry.edit', [$page, $entry])
            ->with('success', 'Eintrag angelegt.');
    }

    public function updateEntry(Request $request, Page $page, PageEntry $entry): RedirectResponse
    {
        $this->authorizePage($page);
        abort_if($entry->page_id !== $page->id, 403);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:200'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $coverPath = $entry->cover_image;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('pages', 'public');
        }

        $entry->update([
            'title'       => $data['title'],
            'cover_image' => $coverPath,
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

    public function storeBlock(Request $request, Page $page, PageEntry $entry): RedirectResponse
    {
        $this->authorizePage($page);
        abort_if($entry->page_id !== $page->id, 403);

        $data = $request->validate([
            'type'    => ['required', 'in:text,heading,image,badge'],
            'content' => ['nullable', 'string', 'max:500'],
            'color'   => ['nullable', 'in:green,blue,orange,gray'],
        ]);

        $nextOrder = PageEntryBlock::where('page_entry_id', $entry->id)->max('sort_order') ?? 0;

        PageEntryBlock::create([
            'page_entry_id' => $entry->id,
            'type'          => $data['type'],
            'content'       => $data['content'] ?? '',
            'color'         => $data['color'] ?? null,
            'sort_order'    => $nextOrder + 1,
        ]);

        return back()->with('success', 'Block hinzugefügt.');
    }

    public function updateBlock(Request $request, Page $page, PageEntry $entry, PageEntryBlock $block): RedirectResponse
    {
        $this->authorizePage($page);
        abort_if($block->page_entry_id !== $entry->id, 403);

        $data = $request->validate([
            'content' => ['nullable', 'string', 'max:500'],
            'color'   => ['nullable', 'in:green,blue,orange,gray'],
        ]);

        $block->update([
            'content' => $data['content'] ?? '',
            'color'   => $data['color'] ?? null,
        ]);

        return back()->with('success', 'Block gespeichert.');
    }

    public function destroyBlock(Page $page, PageEntry $entry, PageEntryBlock $block): RedirectResponse
    {
        $this->authorizePage($page);
        abort_if($block->page_entry_id !== $entry->id, 403);
        $block->delete();

        return back()->with('success', 'Block gelöscht.');
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
