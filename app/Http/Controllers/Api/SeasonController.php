<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Season;

class SeasonController extends Controller
{
    public function index()
    {
        return Season::ordered()
            ->get()
            ->map(fn ($s) => [
                'name'            => $s->name,
                'from'            => $s->from->toDateString(),
                'to'              => $s->to->toDateString(),
                'price_per_night' => $s->price_per_night,
                'min_nights'      => $s->min_nights,
                'badge_color'     => $s->badge_color,
            ]);
    }
}
