<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\TemplateSection;
use App\Models\TemplateSectionContent;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    const SECTIONS = [
        ['section_key' => 'header',    'sort_order' => 0],
        ['section_key' => 'hero',      'sort_order' => 1],
        ['section_key' => 'about',     'sort_order' => 2],
        ['section_key' => 'amenities', 'sort_order' => 3],
        ['section_key' => 'gallery',   'sort_order' => 4],
        ['section_key' => 'pricing',   'sort_order' => 5],
        ['section_key' => 'arrival',   'sort_order' => 6],
        ['section_key' => 'contact',   'sort_order' => 7],
        ['section_key' => 'footer',    'sort_order' => 99],
    ];

    public function run(): void
    {
        $template = Template::firstOrCreate(
            ['slug' => 'fewo-01'],
            ['name' => 'FEWO-01', 'is_active' => true]
        );

        $defaults = [
            'hero' => [
                'eyebrow' => 'Willkommen',
                'title' => 'Ihr Urlaub am Meer',
                'subtitle' => 'Ferienwohnung Mustermann in ruhiger Lage – nah am Strand.',
            ],
            'about' => [
                'eyebrow' => 'Willkommen',
                'title' => 'Ferienwohnung Mustermann',
                'text_1' => 'Unsere Ferienwohnung befindet sich in einem Einfamilienhaus in ruhiger Umgebung, nur wenige Minuten vom Strand entfernt.',
                'text_2' => 'Die Region bietet kilometerlange Sandstrände, malerische Natur und zahlreiche Ausflugsziele für die ganze Familie.',
                'text_3' => 'Die ca. 30 m² große Wohnung verfügt über einen separaten Eingang und ist aufgeteilt in einem Wohn-/Schlafraum, kleinem Flur, separater Küche sowie einem Badezimmer.',
                'card_1_icon' => 'beach_access',
                'card_1_heading' => 'Strand & Meer',
                'card_1_text' => 'Nur wenige Minuten bis zum nächsten Strand',
                'card_2_icon' => 'park',
                'card_2_heading' => 'Natur pur',
                'card_2_text' => 'Wälder, Felder und frische Meeresluft',
                'card_3_icon' => 'directions_bike',
                'card_3_heading' => 'Fahrradfreundlich',
                'card_3_text' => 'Radwege direkt vor der Tür',
                'card_4_icon' => 'theater_comedy',
                'card_4_heading' => 'Kultur & Events',
                'card_4_text' => 'Veranstaltungen und Sehenswürdigkeiten in der Nähe',
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
                'amenity_1_label' => 'Kostenfreier Stellplatz',
                'amenity_2_icon' => 'wifi',
                'amenity_2_label' => 'W-LAN inklusive',
                'amenity_3_icon' => 'tv',
                'amenity_3_label' => 'Fernseher',
                'amenity_4_icon' => 'cooking',
                'amenity_4_label' => 'Herd & Backofen',
                'amenity_5_icon' => 'kitchen',
                'amenity_5_label' => 'Kühlschrank',
                'amenity_6_icon' => 'coffee_maker',
                'amenity_6_label' => 'Kaffeemaschine',
                'amenity_7_icon' => 'kettle',
                'amenity_7_label' => 'Wasserkocher',
                'amenity_8_icon' => 'bed',
                'amenity_8_label' => 'Bettwäsche inklusive',
                'amenity_9_icon' => 'dry',
                'amenity_9_label' => 'Handtücher inklusive',
                'amenity_10_icon' => 'door_front',
                'amenity_10_label' => 'Separater Eingang',
            ],
            'gallery' => [
                'eyebrow' => 'Eindrücke',
                'title' => 'Galerie',
            ],
            'header' => [
                'brand_type' => 'text',
                'brand_name' => 'Ferienwohnung Mustermann',
                'brand_sub'  => 'Ostsee · Musterort',
            ],
            'footer' => [
                'brand_name'         => 'Ferienwohnung Mustermann',
                'brand_sub'          => 'Ostsee · Musterort · Musterregion',
                'contact_name'       => 'Max Mustermann',
                'contact_street'     => 'Musterstraße 1, 12345 Musterstadt',
                'contact_phone'      => '01234 56789',
                'contact_phone_href' => '+4912345678',
                'contact_email'      => 'info@mustermann-fewo.de',
                'copyright'          => '© 2026 Ferienwohnung Mustermann – Alle Rechte vorbehalten',
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
