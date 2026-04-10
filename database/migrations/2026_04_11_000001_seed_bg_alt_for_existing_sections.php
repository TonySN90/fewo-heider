<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // amenities und contact hatten bisher section--alt hardcodiert
        foreach (['amenities', 'contact'] as $sectionKey) {
            $sectionIds = DB::table('template_sections')
                ->where('section_key', $sectionKey)
                ->pluck('id');

            foreach ($sectionIds as $id) {
                DB::table('template_section_content')->updateOrInsert(
                    ['template_section_id' => $id, 'field_key' => 'bg_alt'],
                    ['value' => '1']
                );
            }
        }
    }

    public function down(): void
    {
        foreach (['amenities', 'contact'] as $sectionKey) {
            $sectionIds = DB::table('template_sections')
                ->where('section_key', $sectionKey)
                ->pluck('id');

            foreach ($sectionIds as $id) {
                DB::table('template_section_content')
                    ->where('template_section_id', $id)
                    ->where('field_key', 'bg_alt')
                    ->delete();
            }
        }
    }
};
