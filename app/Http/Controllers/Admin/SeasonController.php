<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingNote;
use App\Models\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::ordered()->get();
        $notes   = PricingNote::ordered()->get();

        return view('admin.seasons', compact('seasons', 'notes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'from'            => ['required', 'date'],
            'to'              => ['required', 'date', 'after_or_equal:from'],
            'price_per_night' => ['required', 'integer', 'min:1'],
            'min_nights'      => ['required', 'integer', 'min:1'],
            'sort_order'      => ['required', 'integer', 'min:0'],
            'badge_color'     => ['nullable', 'string', 'in:blue,green,orange,gold'],
        ]);

        Season::create($data);

        return back()->with('success', 'Saison wurde gespeichert.');
    }

    public function update(Request $request, Season $season)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'from'            => ['required', 'date'],
            'to'              => ['required', 'date', 'after_or_equal:from'],
            'price_per_night' => ['required', 'integer', 'min:1'],
            'min_nights'      => ['required', 'integer', 'min:1'],
            'sort_order'      => ['required', 'integer', 'min:0'],
            'badge_color'     => ['nullable', 'string', 'in:blue,green,orange,gold'],
        ]);

        $season->update($data);

        return back()->with('success', 'Saison wurde aktualisiert.');
    }

    public function destroy(Season $season)
    {
        $season->delete();

        return back()->with('success', 'Saison wurde gelöscht.');
    }
}
