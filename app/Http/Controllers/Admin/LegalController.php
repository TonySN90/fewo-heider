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

        $datenschutz = LegalPage::firstWhere('type', 'datenschutz');
        $impressum   = LegalPage::firstWhere('type', 'impressum');

        return view('admin.legal', compact('datenschutz', 'impressum'));
    }

    public function update(Request $request): RedirectResponse
    {
        $tenant = current_tenant();
        abort_unless($tenant, 404);

        $data = $request->validate([
            'type'    => ['required', 'in:datenschutz,impressum'],
            'content' => ['nullable', 'string'],
        ]);

        LegalPage::updateOrCreate(
            ['tenant_id' => $tenant->id, 'type' => $data['type']],
            ['content' => $data['content'] ?? ''],
        );

        $label = $data['type'] === 'datenschutz' ? 'Datenschutzerklärung' : 'Impressum';

        return back()->with('success', "{$label} gespeichert.");
    }
}
