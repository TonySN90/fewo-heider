<?php

namespace App\Http\Controllers;

use App\Models\PageGroup;
use Illuminate\View\View;
use RalphJSmit\Laravel\SEO\Support\SEOData;

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

        $this->applySeo($group);

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

        $this->applySeo($page);

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

        $this->applySeo($entry);

        return view('pages.entry', compact('group', 'page', 'entry'));
    }

    private function applySeo(mixed $model): void
    {
        $dynamic  = $model->getDynamicSEOData();
        $isEn     = app()->getLocale() === 'en';
        $seoTitle = $isEn
            ? ($model->seo?->title_en ?: $model->seo?->title ?: $dynamic->title)
            : ($model->seo?->title ?: $dynamic->title);
        $seoDesc  = $isEn
            ? ($model->seo?->description_en ?: $model->seo?->description ?: $dynamic->description)
            : ($model->seo?->description ?: $dynamic->description);

        seo()->for(new SEOData(
            title: $seoTitle,
            description: $seoDesc,
            image: $dynamic->image,
        ));
    }
}
