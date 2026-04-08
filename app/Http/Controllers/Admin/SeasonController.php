<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingNote;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::ordered()->with(['prices' => fn ($q) => $q->ordered()])->get();
        $notes = PricingNote::ordered()->get();

        return view('admin.seasons', compact('seasons', 'notes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => ['required', 'integer', 'min:2020', 'max:2099',
                Rule::unique('seasons', 'year')->where('tenant_id', current_tenant()?->id)],
            'name' => ['required', 'string', 'max:100'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        Season::create($data);

        return back()->with('success', 'Saison wurde angelegt.');
    }

    public function update(Request $request, Season $season)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $season->update($data);

        return back()->with('success', 'Saison wurde aktualisiert.');
    }

    public function destroy(Season $season)
    {
        $season->delete();

        return back()->with('success', 'Saison wurde gelöscht.');
    }

    public function activate(Season $season)
    {
        $season->activate();

        return back()->with('success', "Saison \"{$season->name}\" ist jetzt aktiv.");
    }
}
