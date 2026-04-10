<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\TemplateSection;
use App\Models\TemplateSectionContent;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    const SECTIONS = [
        ['section_key' => 'hero',      'sort_order' => 1],
        ['section_key' => 'about',     'sort_order' => 2],
        ['section_key' => 'amenities', 'sort_order' => 3],
        ['section_key' => 'gallery',   'sort_order' => 4],
        ['section_key' => 'pricing',   'sort_order' => 5],
        ['section_key' => 'arrival',   'sort_order' => 6],
        ['section_key' => 'contact',   'sort_order' => 7],
    ];

    public function run(): void
    {
        $template = Template::firstOrCreate(
            ['slug' => 'fresh_green'],
            ['name' => 'Fresh Green', 'is_active' => true]
        );

        $defaults = [
            'hero' => [
                'eyebrow' => 'Willkommen',
                'title' => 'Ihr Urlaub auf Rügen',
                'subtitle' => 'Ferienwohnung Heider in ruhiger Lage – nur 3 km vom Ostseebad Binz entfernt.',
            ],
            'about' => [
                'eyebrow' => 'Willkommen',
                'title' => 'Ferienwohnung Heider',
                'text_1' => 'Die Ferienwohnung befindet sich in einem Einfamilienhaus in ruhiger Umgebung in der Nähe vom Ostseebad Binz. Perfekt, um schnell mit dem Fahrrad in das nur 3 km entfernte Ostseebad zu radeln.',
                'text_2' => 'Die Insel Rügen erwartet Sie mit 60 km langen Sandstränden, weißer Kreideküste, wunderschöner Natur, den renommierten Störtebeker Festspielen und vielem mehr.',
                'text_3' => 'Die ca. 30 m² große Wohnung verfügt über einen separaten Eingang und ist aufgeteilt in einem Wohn-/Schlafraum, kleinem Flur, separater Küche sowie einem Badezimmer. Vor dem Eingang befindet sich eine kleine gemütliche Sitzecke – ideal für ein Glas Wein zum Ausklang des Tages.',
                'card_1_icon' => 'beach_access',
                'card_1_heading' => 'Strand & Meer',
                'card_1_text' => '60 km Sandstrand – Binz nur 3 km entfernt',
                'card_2_icon' => 'park',
                'card_2_heading' => 'Natur pur',
                'card_2_text' => 'Kreideküste, Nationalpark & Buchenwälder',
                'card_3_icon' => 'directions_bike',
                'card_3_heading' => 'Fahrradfreundlich',
                'card_3_text' => 'Direkte Radweganbindung zum Ostseebad',
                'card_4_icon' => 'theater_comedy',
                'card_4_heading' => 'Kultur & Events',
                'card_4_text' => 'Störtebeker Festspiele & lokale Veranstaltungen',
                'card_5_icon' => '',
                'card_5_heading' => '',
                'card_5_text' => '',
                'card_6_icon' => '',
                'card_6_heading' => '',
                'card_6_text' => '',
            ],
            'amenities' => [
                'eyebrow' => 'Was wir bieten',
                'title' => 'Ausstattung',
                'amenity_1_icon' => 'local_parking',
                'amenity_1_label' => 'Kostenfreier Stellparkplatz',
                'amenity_2_icon' => 'wifi',
                'amenity_2_label' => 'W-LAN inklusive',
                'amenity_3_icon' => 'radio',
                'amenity_3_label' => 'Radio mit CD-Player',
                'amenity_4_icon' => 'tv',
                'amenity_4_label' => 'Satelliten-Fernsehen',
                'amenity_5_icon' => 'cooking',
                'amenity_5_label' => 'Herd & Backofen',
                'amenity_6_icon' => 'kitchen',
                'amenity_6_label' => 'Kühlschrank mit Tiefkühlfach',
                'amenity_7_icon' => 'coffee_maker',
                'amenity_7_label' => 'Kaffeemaschine',
                'amenity_8_icon' => 'breakfast_dining',
                'amenity_8_label' => 'Toaster',
                'amenity_9_icon' => 'kettle',
                'amenity_9_label' => 'Wasserkocher',
                'amenity_10_icon' => 'egg_alt',
                'amenity_10_label' => 'Eierkocher',
                'amenity_11_icon' => 'bed',
                'amenity_11_label' => 'Bettwäsche inklusive',
                'amenity_12_icon' => 'dry',
                'amenity_12_label' => 'Handtücher inklusive',
                'amenity_13_icon' => 'door_front',
                'amenity_13_label' => 'Separater Eingang',
                'amenity_14_icon' => 'chair',
                'amenity_14_label' => 'Gemütliche Sitzecke',
            ],
            'gallery' => [
                'eyebrow' => 'Eindrücke',
                'title' => 'Galerie',
            ],
        ];

        foreach (self::SECTIONS as $section) {
            $sec = TemplateSection::firstOrCreate(
                ['template_id' => $template->id, 'section_key' => $section['section_key']],
                array_merge($section, ['template_id' => $template->id, 'is_visible' => true])
            );

            foreach ($defaults[$sec->section_key] ?? [] as $key => $value) {
                TemplateSectionContent::firstOrCreate(
                    ['template_section_id' => $sec->id, 'field_key' => $key],
                    ['value' => $value]
                );
            }
        }
    }
}
