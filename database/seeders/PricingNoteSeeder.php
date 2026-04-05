<?php

namespace Database\Seeders;

use App\Models\PricingNote;
use Illuminate\Database\Seeder;

class PricingNoteSeeder extends Seeder
{
    public function run(): void
    {
        $notes = [
            ['icon' => 'check_circle', 'text' => 'Bettwäsche & Handtücher inklusive', 'sort_order' => 1],
            ['icon' => 'check_circle', 'text' => 'Endreinigung: 35 €',                'sort_order' => 2],
            ['icon' => 'pets',         'text' => 'Haustiere: auf Anfrage',             'sort_order' => 3],
        ];

        foreach ($notes as $note) {
            PricingNote::firstOrCreate(['text' => $note['text']], $note);
        }
    }
}
