<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenantTheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function edit(): View
    {
        $tenant = current_tenant();
        abort_unless($tenant, 404);

        $theme = $tenant->theme ?? new TenantTheme();

        return view('admin.theme', compact('theme'));
    }

    public function update(Request $request): RedirectResponse
    {
        $tenant = current_tenant();
        abort_unless($tenant, 404);

        $data = $request->validate([
            'color_primary'      => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'color_primary_dark' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'color_secondary'    => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'color_bg'           => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'color_bg_alt'       => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'color_border'       => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'color_footer_top'   => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'color_footer_bot'   => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        // Leere Strings zu null
        $data = array_map(fn ($v) => $v === '' ? null : $v, $data);

        TenantTheme::updateOrCreate(
            ['tenant_id' => $tenant->id],
            $data
        );

        return back()->with('success', 'Theme gespeichert.');
    }
}
