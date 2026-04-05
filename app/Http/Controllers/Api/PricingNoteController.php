<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PricingNote;

class PricingNoteController extends Controller
{
    public function index()
    {
        return PricingNote::ordered()->get()->map(fn ($n) => [
            'text'       => $n->text,
            'icon'       => $n->icon,
            'sort_order' => $n->sort_order,
        ]);
    }
}
