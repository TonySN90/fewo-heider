<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalController extends Controller
{
    public function edit(): View
    {
        $tenant = current_tenant();
        abort_unless($tenant, 404);

        $pages = LegalPage::get()->groupBy(fn ($p) => $p->type . '_' . $p->locale);

        return view('admin.legal', [
            'datenschutz_de' => $pages->get('datenschutz_de')?->first(),
            'datenschutz_en' => $pages->get('datenschutz_en')?->first(),
            'impressum_de'   => $pages->get('impressum_de')?->first(),
            'impressum_en'   => $pages->get('impressum_en')?->first(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $tenant = current_tenant();
        abort_unless($tenant, 404);

        $data = $request->validate([
            'type'    => ['required', 'in:datenschutz,impressum'],
            'locale'  => ['required', 'in:de,en'],
            'content' => ['nullable', 'string'],
        ]);

        LegalPage::updateOrCreate(
            ['tenant_id' => $tenant->id, 'type' => $data['type'], 'locale' => $data['locale']],
            ['content' => $data['content'] ?? ''],
        );

        $label = $data['type'] === 'datenschutz' ? 'Datenschutzerklärung' : 'Impressum';
        $lang  = $data['locale'] === 'de' ? 'DE' : 'EN';

        return back()->with('success', "{$label} ({$lang}) gespeichert.");
    }
}
