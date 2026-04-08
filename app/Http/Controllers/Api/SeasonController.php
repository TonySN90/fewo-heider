<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Season;

class SeasonController extends Controller
{
    public function index()
    {
        $season = Season::where('is_active', true)->first();

        if (! $season) {
            return response()->json(['year' => null, 'prices' => []]);
        }

        return response()->json([
            'year' => $season->year,
            'prices' => $season->prices()->orderBy('sort_order')->orderBy('from')->get()->map(fn ($p) => [
                'name' => $p->name,
                'from' => $p->from->toDateString(),
                'to' => $p->to->toDateString(),
                'price_per_night' => $p->price_per_night,
                'min_nights' => $p->min_nights,
                'badge_color' => $p->badge_color,
            ]),
        ]);
    }
}
