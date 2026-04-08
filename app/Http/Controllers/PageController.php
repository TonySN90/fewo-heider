<?php

namespace App\Http\Controllers;

use App\Models\PageGroup;
use Illuminate\View\View;

class PageController extends Controller
{
    public function groupIndex(string $groupSlug): View
    {
        $tenant = current_tenant();
        $group = PageGroup::where('tenant_id', $tenant?->id)
            ->where('slug', $groupSlug)
            ->where('is_visible', true)
            ->firstOrFail();

        $pages = $group->pages()->where('is_visible', true)->get();

        return view('pages.group', compact('group', 'pages'));
    }

    public function show(string $groupSlug, string $pageSlug): View
    {
        $tenant = current_tenant();
        $group = PageGroup::where('tenant_id', $tenant?->id)
            ->where('slug', $groupSlug)
            ->where('is_visible', true)
            ->firstOrFail();

        $page = $group->pages()
            ->where('slug', $pageSlug)
            ->where('is_visible', true)
            ->firstOrFail();

        $page->load('entries.blocks');

        return view('pages.show', compact('group', 'page'));
    }

    public function entry(string $groupSlug, string $pageSlug, string $entrySlug): View
    {
        $tenant = current_tenant();
        $group = PageGroup::where('tenant_id', $tenant?->id)
            ->where('slug', $groupSlug)
            ->where('is_visible', true)
            ->firstOrFail();

        $page = $group->pages()
            ->where('slug', $pageSlug)
            ->where('is_visible', true)
            ->firstOrFail();

        $entry = $page->entries()->where('slug', $entrySlug)->firstOrFail();
        $entry->load('blocks');

        return view('pages.entry', compact('group', 'page', 'entry'));
    }
}
