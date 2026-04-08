<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingNote;
use Illuminate\Http\Request;

class PricingNoteController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'text' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        PricingNote::create($data);

        return back()->with('success', 'Hinweis wurde gespeichert.');
    }

    public function update(Request $request, PricingNote $note)
    {
        $data = $request->validate([
            'text' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $note->update($data);

        return back()->with('success', 'Hinweis wurde aktualisiert.');
    }

    public function destroy(PricingNote $note)
    {
        $note->delete();

        return back()->with('success', 'Hinweis wurde gelöscht.');
    }
}
