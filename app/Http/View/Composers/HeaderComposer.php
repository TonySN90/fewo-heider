<?php

namespace App\Http\View\Composers;

use App\Models\PageGroup;
use App\Models\Template;
use Illuminate\View\View;

class HeaderComposer
{
    public function compose(View $view): void
    {
        $tenant = current_tenant();

        $headerSection   = null;
        $orderedSections = collect();
        $visibleSections = [];

        if ($tenant !== null && $tenant->template_id !== null) {
            $template = Template::find($tenant->template_id);

            if ($template !== null) {
                $sections = $template->sectionsForTenant($tenant->id)
                    ->with('content')
                    ->get();

                $headerSection   = $sections->firstWhere('section_key', 'header');
                $visibleSections = $sections->where('is_visible', true)->pluck('section_key')->toArray();
                $orderedSections = $sections->where('is_visible', true)->values();
            }
        }

        $pageGroups = $tenant
            ? PageGroup::where('tenant_id', $tenant->id)
                ->where('is_visible', true)
                ->orderBy('sort_order')
                ->get()
            : collect();

        $view->with([
            'headerSection'   => $headerSection,
            'orderedSections' => $orderedSections,
            'visibleSections' => $visibleSections,
            'pageGroups'      => $pageGroups,
            'ui'              => ui_labels(),
        ]);
    }
}
