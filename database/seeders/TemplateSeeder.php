<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\TemplateSection;
use App\Models\TemplateSectionContent;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    const SECTIONS = [
        ['section_key' => 'hero',        'sort_order' => 1],
        ['section_key' => 'ueber-uns',   'sort_order' => 2],
        ['section_key' => 'ausstattung', 'sort_order' => 3],
        ['section_key' => 'galerie',     'sort_order' => 4],
        ['section_key' => 'preise',      'sort_order' => 5],
        ['section_key' => 'anreise',     'sort_order' => 6],
        ['section_key' => 'kontakt',     'sort_order' => 7],
    ];

    public function run(): void
    {
        $template = Template::firstOrCreate(
            ['slug' => 'fresh_green'],
            ['name' => 'Fresh Green', 'is_active' => true]
        );

        $heroDefaults = [
            'eyebrow'  => 'Willkommen',
            'title'    => 'Ihr Urlaub auf Rügen',
            'subtitle' => 'Ferienwohnung Heider in ruhiger Lage – nur 3 km vom Ostseebad Binz entfernt.',
        ];

        foreach (self::SECTIONS as $section) {
            $sec = TemplateSection::firstOrCreate(
                ['template_id' => $template->id, 'section_key' => $section['section_key']],
                array_merge($section, ['template_id' => $template->id, 'is_visible' => true])
            );

            if ($sec->section_key === 'hero') {
                foreach ($heroDefaults as $key => $value) {
                    TemplateSectionContent::firstOrCreate(
                        ['template_section_id' => $sec->id, 'field_key' => $key],
                        ['value' => $value]
                    );
                }
            }
        }
    }
}
