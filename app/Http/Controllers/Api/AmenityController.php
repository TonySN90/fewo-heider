<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Template;

class AmenityController extends Controller
{
    public function index()
    {
        $template = Template::active();
        $section  = $template?->sections->firstWhere('section_key', 'ausstattung');
        $section?->load('content');

        $items = [];
        for ($i = 1; $i <= 50; $i++) {
            $icon  = $section?->field("amenity_{$i}_icon");
            $label = $section?->field("amenity_{$i}_label");
            if ($icon && $label) {
                $items[] = ['icon' => $icon, 'label' => $label];
            } elseif (!$icon && !$label) {
                break;
            }
        }

        return response()->json($items);
    }
}
