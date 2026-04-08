<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Seeder;

class IconSeeder extends Seeder
{
    public function run(): void
    {
        $icons = [
            // Strand & Meer
            ['name' => 'beach_access',    'label' => 'Strand',              'group' => 'Natur & Outdoor', 'sort_order' => 1],
            ['name' => 'waves',           'label' => 'Wellen',              'group' => 'Natur & Outdoor', 'sort_order' => 2],
            ['name' => 'water',           'label' => 'Wasser',              'group' => 'Natur & Outdoor', 'sort_order' => 3],
            ['name' => 'anchor',          'label' => 'Anker',               'group' => 'Natur & Outdoor', 'sort_order' => 4],
            ['name' => 'sailing',         'label' => 'Segeln',              'group' => 'Natur & Outdoor', 'sort_order' => 5],
            ['name' => 'kayaking',        'label' => 'Kajak',               'group' => 'Natur & Outdoor', 'sort_order' => 6],
            ['name' => 'surfing',         'label' => 'Surfen',              'group' => 'Natur & Outdoor', 'sort_order' => 7],
            ['name' => 'park',            'label' => 'Park / Natur',        'group' => 'Natur & Outdoor', 'sort_order' => 8],
            ['name' => 'forest',          'label' => 'Wald',                'group' => 'Natur & Outdoor', 'sort_order' => 9],
            ['name' => 'landscape',       'label' => 'Landschaft',          'group' => 'Natur & Outdoor', 'sort_order' => 10],
            ['name' => 'sunny',           'label' => 'Sonne',               'group' => 'Natur & Outdoor', 'sort_order' => 11],
            ['name' => 'wb_sunny',        'label' => 'Sonnig',              'group' => 'Natur & Outdoor', 'sort_order' => 12],
            ['name' => 'air',             'label' => 'Wind / Luft',         'group' => 'Natur & Outdoor', 'sort_order' => 13],
            ['name' => 'eco',             'label' => 'Natur / Eco',         'group' => 'Natur & Outdoor', 'sort_order' => 14],

            // Sport & Freizeit
            ['name' => 'directions_bike', 'label' => 'Fahrrad',             'group' => 'Sport & Freizeit', 'sort_order' => 1],
            ['name' => 'hiking',          'label' => 'Wandern',             'group' => 'Sport & Freizeit', 'sort_order' => 2],
            ['name' => 'sports_tennis',   'label' => 'Tennis',              'group' => 'Sport & Freizeit', 'sort_order' => 3],
            ['name' => 'fitness_center',  'label' => 'Fitness',             'group' => 'Sport & Freizeit', 'sort_order' => 4],
            ['name' => 'sports_soccer',   'label' => 'Fußball',             'group' => 'Sport & Freizeit', 'sort_order' => 5],
            ['name' => 'pool',            'label' => 'Pool / Schwimmen',    'group' => 'Sport & Freizeit', 'sort_order' => 6],
            ['name' => 'golf_course',     'label' => 'Golf',                'group' => 'Sport & Freizeit', 'sort_order' => 7],
            ['name' => 'downhill_skiing', 'label' => 'Skifahren',           'group' => 'Sport & Freizeit', 'sort_order' => 8],
            ['name' => 'theater_comedy',  'label' => 'Kultur / Theater',    'group' => 'Sport & Freizeit', 'sort_order' => 9],
            ['name' => 'casino',          'label' => 'Casino',              'group' => 'Sport & Freizeit', 'sort_order' => 10],
            ['name' => 'spa',             'label' => 'Wellness / Spa',      'group' => 'Sport & Freizeit', 'sort_order' => 11],
            ['name' => 'self_care',       'label' => 'Entspannung',         'group' => 'Sport & Freizeit', 'sort_order' => 12],

            // Unterkunft & Ausstattung
            ['name' => 'bed',             'label' => 'Bett',                'group' => 'Unterkunft', 'sort_order' => 1],
            ['name' => 'bedroom_parent',  'label' => 'Schlafzimmer',        'group' => 'Unterkunft', 'sort_order' => 2],
            ['name' => 'chair',           'label' => 'Sitzecke',            'group' => 'Unterkunft', 'sort_order' => 3],
            ['name' => 'door_front',      'label' => 'Separater Eingang',   'group' => 'Unterkunft', 'sort_order' => 4],
            ['name' => 'balcony',         'label' => 'Balkon / Terrasse',   'group' => 'Unterkunft', 'sort_order' => 5],
            ['name' => 'deck',            'label' => 'Terrasse',            'group' => 'Unterkunft', 'sort_order' => 6],
            ['name' => 'fireplace',       'label' => 'Kamin',               'group' => 'Unterkunft', 'sort_order' => 7],
            ['name' => 'hot_tub',         'label' => 'Whirlpool',           'group' => 'Unterkunft', 'sort_order' => 8],
            ['name' => 'bathtub',         'label' => 'Badewanne',           'group' => 'Unterkunft', 'sort_order' => 9],
            ['name' => 'shower',          'label' => 'Dusche',              'group' => 'Unterkunft', 'sort_order' => 10],
            ['name' => 'dry',             'label' => 'Handtücher',          'group' => 'Unterkunft', 'sort_order' => 11],
            ['name' => 'iron',            'label' => 'Bügeleisen',          'group' => 'Unterkunft', 'sort_order' => 12],
            ['name' => 'local_laundry_service', 'label' => 'Waschmaschine', 'group' => 'Unterkunft', 'sort_order' => 13],

            // Technik & Komfort
            ['name' => 'wifi',            'label' => 'W-LAN',               'group' => 'Technik', 'sort_order' => 1],
            ['name' => 'tv',              'label' => 'Fernseher',           'group' => 'Technik', 'sort_order' => 2],
            ['name' => 'radio',           'label' => 'Radio',               'group' => 'Technik', 'sort_order' => 3],
            ['name' => 'air_purifier',    'label' => 'Klimaanlage',         'group' => 'Technik', 'sort_order' => 4],
            ['name' => 'heat',            'label' => 'Heizung',             'group' => 'Technik', 'sort_order' => 5],

            // Küche
            ['name' => 'cooking',         'label' => 'Herd & Backofen',     'group' => 'Küche', 'sort_order' => 1],
            ['name' => 'kitchen',         'label' => 'Kühlschrank',         'group' => 'Küche', 'sort_order' => 2],
            ['name' => 'coffee_maker',    'label' => 'Kaffeemaschine',      'group' => 'Küche', 'sort_order' => 3],
            ['name' => 'breakfast_dining', 'label' => 'Toaster',             'group' => 'Küche', 'sort_order' => 4],
            ['name' => 'kettle',          'label' => 'Wasserkocher',        'group' => 'Küche', 'sort_order' => 5],
            ['name' => 'egg_alt',         'label' => 'Eierkocher',          'group' => 'Küche', 'sort_order' => 6],
            ['name' => 'microwave',       'label' => 'Mikrowelle',          'group' => 'Küche', 'sort_order' => 7],
            ['name' => 'blender',         'label' => 'Mixer',               'group' => 'Küche', 'sort_order' => 8],
            ['name' => 'dining',          'label' => 'Essbereich',          'group' => 'Küche', 'sort_order' => 9],
            ['name' => 'restaurant',      'label' => 'Restaurant',          'group' => 'Küche', 'sort_order' => 10],
            ['name' => 'local_cafe',      'label' => 'Café',                'group' => 'Küche', 'sort_order' => 11],
            ['name' => 'bakery_dining',   'label' => 'Bäckerei',            'group' => 'Küche', 'sort_order' => 12],

            // Parken & Anreise
            ['name' => 'local_parking',   'label' => 'Parkplatz',           'group' => 'Anreise', 'sort_order' => 1],
            ['name' => 'garage',          'label' => 'Garage',              'group' => 'Anreise', 'sort_order' => 2],
            ['name' => 'directions_car',  'label' => 'Auto',                'group' => 'Anreise', 'sort_order' => 3],
            ['name' => 'train',           'label' => 'Zug / Bahn',          'group' => 'Anreise', 'sort_order' => 4],
            ['name' => 'flight',          'label' => 'Flugzeug',            'group' => 'Anreise', 'sort_order' => 5],
            ['name' => 'directions_bus',  'label' => 'Bus',                 'group' => 'Anreise', 'sort_order' => 6],
            ['name' => 'pedal_bike',      'label' => 'Fahrrad (Anreise)',   'group' => 'Anreise', 'sort_order' => 7],
            ['name' => 'ev_station',      'label' => 'E-Ladestation',       'group' => 'Anreise', 'sort_order' => 8],

            // Familie & Haustiere
            ['name' => 'family_restroom', 'label' => 'Familie',             'group' => 'Familie', 'sort_order' => 1],
            ['name' => 'child_care',      'label' => 'Kinder',              'group' => 'Familie', 'sort_order' => 2],
            ['name' => 'crib',            'label' => 'Kinderbett',          'group' => 'Familie', 'sort_order' => 3],
            ['name' => 'toys',            'label' => 'Spielzeug',           'group' => 'Familie', 'sort_order' => 4],
            ['name' => 'pets',            'label' => 'Haustiere erlaubt',   'group' => 'Familie', 'sort_order' => 5],

            // Allgemein
            ['name' => 'star',            'label' => 'Highlight / Stern',   'group' => 'Allgemein', 'sort_order' => 1],
            ['name' => 'favorite',        'label' => 'Herz / Favorit',      'group' => 'Allgemein', 'sort_order' => 2],
            ['name' => 'home',            'label' => 'Haus',                'group' => 'Allgemein', 'sort_order' => 3],
            ['name' => 'key',             'label' => 'Schlüssel',           'group' => 'Allgemein', 'sort_order' => 4],
            ['name' => 'check_circle',    'label' => 'Häkchen',             'group' => 'Allgemein', 'sort_order' => 5],
            ['name' => 'info',            'label' => 'Info',                'group' => 'Allgemein', 'sort_order' => 6],
            ['name' => 'warning',         'label' => 'Warnung',             'group' => 'Allgemein', 'sort_order' => 7],
            ['name' => 'euro',            'label' => 'Euro / Preis',        'group' => 'Allgemein', 'sort_order' => 8],
            ['name' => 'calendar_month',  'label' => 'Kalender',            'group' => 'Allgemein', 'sort_order' => 9],
            ['name' => 'schedule',        'label' => 'Uhrzeit',             'group' => 'Allgemein', 'sort_order' => 10],
            ['name' => 'phone',           'label' => 'Telefon',             'group' => 'Allgemein', 'sort_order' => 11],
            ['name' => 'mail',            'label' => 'E-Mail',              'group' => 'Allgemein', 'sort_order' => 12],
            ['name' => 'location_on',     'label' => 'Standort',            'group' => 'Allgemein', 'sort_order' => 13],
            ['name' => 'cleaning_services', 'label' => 'Reinigung',          'group' => 'Allgemein', 'sort_order' => 14],
            ['name' => 'no_food',         'label' => 'Kein Essen',          'group' => 'Allgemein', 'sort_order' => 15],
            ['name' => 'smoking_rooms',   'label' => 'Rauchen erlaubt',     'group' => 'Allgemein', 'sort_order' => 16],
            ['name' => 'smoke_free',      'label' => 'Nichtraucher',        'group' => 'Allgemein', 'sort_order' => 17],
            ['name' => 'cake',            'label' => 'Feier / Geburtstag',  'group' => 'Allgemein', 'sort_order' => 18],
        ];

        foreach ($icons as $icon) {
            Icon::firstOrCreate(
                ['name' => $icon['name']],
                $icon
            );
        }
    }
}
