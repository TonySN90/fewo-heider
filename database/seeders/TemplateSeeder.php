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
                'eyebrow'    => 'Willkommen',
                'eyebrow_en' => 'Welcome',
                'title'      => 'Ihr Urlaub am Meer',
                'title_en'   => 'Your Holiday by the Sea',
                'subtitle'   => 'Unsere Ferienwohnung liegt ruhig und zentral – der perfekte Ausgangspunkt für Ihren Urlaub.',
                'subtitle_en' => 'Our holiday apartment is quietly and centrally located – the perfect base for your holiday.',
            ],
            'about' => [
                'eyebrow'          => 'Willkommen',
                'eyebrow_en'       => 'Welcome',
                'title'            => 'Musterferienwohnung',
                'title_en'         => 'Sample Holiday Apartment',
                'text_1'           => 'Die Unterkunft befindet sich in einem ruhigen Wohngebiet und bietet Ihnen alles, was Sie für einen entspannten Urlaub benötigen.',
                'text_1_en'        => 'The accommodation is situated in a quiet residential area and offers everything you need for a relaxing holiday.',
                'text_2'           => 'Die Region lädt ein zu Spaziergängen am Strand, Radtouren durch die Natur und Ausflügen zu kulturellen Sehenswürdigkeiten.',
                'text_2_en'        => 'The region invites you to take walks on the beach, cycle through nature and explore cultural sights.',
                'text_3'           => 'Die ca. 35 m² große Wohnung verfügt über einen separaten Eingang, einen Wohn-/Schlafraum, eine voll ausgestattete Küche sowie ein Badezimmer. Vor dem Eingang lädt eine kleine Sitzecke zum Verweilen ein.',
                'text_3_en'        => 'The approx. 35 m² apartment features a separate entrance, a living/bedroom, a fully equipped kitchen and a bathroom. A small seating area outside the entrance invites you to relax.',
                'card_1_icon'      => 'beach_access',
                'card_1_heading'   => 'Strand & Meer',
                'card_1_heading_en' => 'Beach & Sea',
                'card_1_text'      => 'Strand und Meer in unmittelbarer Nähe',
                'card_1_text_en'   => 'Beach and sea within easy reach',
                'card_2_icon'      => 'park',
                'card_2_heading'   => 'Natur pur',
                'card_2_heading_en' => 'Pure Nature',
                'card_2_text'      => 'Wälder, Felder und frische Meeresluft',
                'card_2_text_en'   => 'Forests, fields and fresh sea air',
                'card_3_icon'      => 'directions_bike',
                'card_3_heading'   => 'Fahrradfreundlich',
                'card_3_heading_en' => 'Cycling-Friendly',
                'card_3_text'      => 'Radwege direkt vor der Tür',
                'card_3_text_en'   => 'Cycle paths right on the doorstep',
                'card_4_icon'      => 'theater_comedy',
                'card_4_heading'   => 'Kultur & Events',
                'card_4_heading_en' => 'Culture & Events',
                'card_4_text'      => 'Veranstaltungen und Sehenswürdigkeiten in der Nähe',
                'card_4_text_en'   => 'Events and attractions nearby',
                'card_5_icon'      => '',
                'card_5_heading'   => '',
                'card_5_text'      => '',
                'card_6_icon'      => '',
                'card_6_heading'   => '',
                'card_6_text'      => '',
            ],
            'amenities' => [
                'eyebrow'          => 'Was wir bieten',
                'eyebrow_en'       => 'What we offer',
                'title'            => 'Ausstattung',
                'title_en'         => 'Amenities',
                'bg_alt'           => '1',
                'amenity_1_icon'   => 'local_parking',
                'amenity_1_label'  => 'Kostenfreier Stellplatz',
                'amenity_2_icon'   => 'wifi',
                'amenity_2_label'  => 'W-LAN inklusive',
                'amenity_3_icon'   => 'tv',
                'amenity_3_label'  => 'Fernseher',
                'amenity_4_icon'   => 'cooking',
                'amenity_4_label'  => 'Herd & Backofen',
                'amenity_5_icon'   => 'kitchen',
                'amenity_5_label'  => 'Kühlschrank',
                'amenity_6_icon'   => 'coffee_maker',
                'amenity_6_label'  => 'Kaffeemaschine',
                'amenity_7_icon'   => 'kettle',
                'amenity_7_label'  => 'Wasserkocher',
                'amenity_8_icon'   => 'breakfast_dining',
                'amenity_8_label'  => 'Toaster',
                'amenity_9_icon'   => 'bed',
                'amenity_9_label'  => 'Bettwäsche inklusive',
                'amenity_10_icon'  => 'dry',
                'amenity_10_label' => 'Handtücher inklusive',
                'amenity_11_icon'  => 'door_front',
                'amenity_11_label' => 'Separater Eingang',
                'amenity_12_icon'  => 'chair',
                'amenity_12_label' => 'Gemütliche Sitzecke',
            ],
            'gallery' => [
                'eyebrow'    => 'Eindrücke',
                'eyebrow_en' => 'Impressions',
                'title'      => 'Galerie',
                'title_en'   => 'Gallery',
            ],
            'header' => [
                'brand_type'    => 'text',
                'brand_name'    => 'Musterferienwohnung',
                'brand_name_en' => 'Sample Holiday Apartment',
                'brand_sub'     => 'Ort · Region',
                'brand_sub_en'  => 'Location · Region',
            ],
            'arrival' => [
                'eyebrow'           => 'So finden Sie uns',
                'eyebrow_en'        => 'How to find us',
                'title'             => 'Anreise',
                'title_en'          => 'Getting Here',
                'address_name'      => 'Max Mustermann',
                'address_subtitle'  => 'Ihre Unterkunft',
                'address_subtitle_en' => 'Your accommodation',
                'address_street'    => 'Musterstraße 1',
                'address_city'      => '12345 Musterstadt',
                'phone'             => '01234 56789',
                'phone_href'        => '+4912345678',
                'email'             => 'info@mustermann-fewo.de',
                'map_lat'           => '54.0000',
                'map_lng'           => '13.0000',
                'hints_title'       => 'Anreise-Tipps',
                'hints_title_en'    => 'How to get here',
                'hint_1_icon'       => 'directions_car',
                'hint_1_text'       => 'Mit dem Auto: Über die Autobahn bis zur Abfahrt Musterstadt, dann ca. 5 Minuten bis zur Unterkunft.',
                'hint_1_text_en'    => 'By car: Take the motorway to the Musterstadt exit, then approx. 5 minutes to the accommodation.',
                'hint_2_icon'       => 'train',
                'hint_2_text'       => 'Mit der Bahn: Bahnhof Musterstadt, dann 10 Minuten mit dem Taxi oder Bus Linie 5.',
                'hint_2_text_en'    => 'By train: Musterstadt station, then 10 minutes by taxi or bus line 5.',
            ],
            'contact' => [
                'eyebrow'       => 'Wir freuen uns auf Sie',
                'eyebrow_en'    => 'We look forward to hearing from you',
                'title'         => 'Kontakt & Anfrage',
                'title_en'      => 'Contact & Enquiry',
                'text_1'        => 'Haben Sie Fragen zu unserer Unterkunft oder möchten Sie einen Aufenthalt anfragen? Schreiben Sie uns – wir antworten so schnell wie möglich.',
                'text_1_en'     => 'Do you have questions about our accommodation or would you like to make an enquiry? Write to us – we will get back to you as soon as possible.',
                'text_2'        => '',
                'text_2_en'     => '',
                'btn_label'     => 'E-Mail schreiben',
                'btn_label_en'  => 'Send an Email',
                'card_label'    => 'Ihre Ansprechpartnerin',
                'card_label_en' => 'Your contact person',
                'card_name'     => 'Max Mustermann',
                'card_address'  => 'Musterstraße 1, 12345 Musterstadt',
                'phone'         => '01234 56789',
                'phone_href'    => '+4912345678',
                'email'         => 'info@mustermann-fewo.de',
                'bg_alt'        => '1',
            ],
            'footer' => [
                'brand_type'         => 'text',
                'brand_name'         => 'Musterferienwohnung',
                'brand_name_en'      => 'Sample Holiday Apartment',
                'brand_sub'          => 'Ort · Region',
                'brand_sub_en'       => 'Location · Region',
                'contact_name'       => 'Max Mustermann',
                'contact_street'     => 'Musterstraße 1, 12345 Musterstadt',
                'contact_phone'      => '01234 56789',
                'contact_phone_href' => '+4912345678',
                'contact_email'      => 'info@mustermann-fewo.de',
                'copyright'          => '© 2026 Musterferienwohnung – Alle Rechte vorbehalten',
                'copyright_en'       => '© 2026 Sample Holiday Apartment – All rights reserved',
            ],
        ];

        foreach (self::SECTIONS as $section) {
            // firstOrCreate funktioniert nicht mit NULL-Werten in SQLite UNIQUE-Constraints.
            // Deshalb explizit per whereNull suchen und ggf. neu anlegen.
            $sec = TemplateSection::where('template_id', $template->id)
                ->where('section_key', $section['section_key'])
                ->whereNull('tenant_id')
                ->first();

            if (! $sec) {
                $sec = TemplateSection::create(
                    array_merge($section, ['template_id' => $template->id, 'tenant_id' => null, 'is_visible' => true])
                );
            }

            foreach ($defaults[$sec->section_key] ?? [] as $key => $value) {
                TemplateSectionContent::firstOrCreate(
                    ['template_section_id' => $sec->id, 'field_key' => $key],
                    ['value' => $value]
                );
            }
        }
    }
}
