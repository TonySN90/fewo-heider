<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Models\SeasonPrice;
use Illuminate\Http\Request;

class SeasonPriceController extends Controller
{
    public function store(Request $request, Season $season)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
            'price_per_night' => ['required', 'integer', 'min:1'],
            'min_nights' => ['required', 'integer', 'min:1'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'badge_color' => ['nullable', 'string', 'in:blue,green,orange,gold'],
        ]);

        $season->prices()->create($data);

        return back()->with('success', 'Preis wurde gespeichert.');
    }

    public function update(Request $request, SeasonPrice $price)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
            'price_per_night' => ['required', 'integer', 'min:1'],
            'min_nights' => ['required', 'integer', 'min:1'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'badge_color' => ['nullable', 'string', 'in:blue,green,orange,gold'],
        ]);

        $price->update($data);

        return back()->with('success', 'Preis wurde aktualisiert.');
    }

    public function destroy(SeasonPrice $price)
    {
        $price->delete();

        return back()->with('success', 'Preis wurde gelöscht.');
    }
}
