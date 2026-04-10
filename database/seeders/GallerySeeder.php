<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use App\Models\Template;
use App\Models\TemplateSection;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $template = Template::where('slug', 'fresh_green')->first();
        if (! $template) {
            return;
        }

        /** @var TemplateSection|null $section */
        $section = $template->sections()->where('section_key', 'gallery')->first();
        if (! $section) {
            return;
        }

        // Nur anlegen wenn noch keine Bilder vorhanden
        if (GalleryImage::where('template_section_id', $section->id)->exists()) {
            return;
        }

        $images = [
            ['filename' => 'front.webp',    'caption' => 'Hauseingang',   'sort_order' => 1],
            ['filename' => 'haus_02.webp',  'caption' => 'Außenansicht',  'sort_order' => 2],
            ['filename' => 'w1.webp',       'caption' => 'Wohnbereich',   'sort_order' => 3],
            ['filename' => 'w2.webp',       'caption' => 'Schlafbereich', 'sort_order' => 4],
            ['filename' => 'k.webp',        'caption' => 'Küche',         'sort_order' => 5],
            ['filename' => 't2.webp',       'caption' => 'Sitzecke',      'sort_order' => 6],
            ['filename' => 't1.webp',       'caption' => 'Terrasse',      'sort_order' => 7],
            ['filename' => 't3.webp',       'caption' => 'Außenbereich',  'sort_order' => 8],
        ];

        foreach ($images as $image) {
            GalleryImage::create(array_merge($image, [
                'template_section_id' => $section->id,
            ]));
        }
    }
}
