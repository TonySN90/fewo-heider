<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const MAP = [
        'ueber-uns'   => 'about',
        'ausstattung' => 'amenities',
        'galerie'     => 'gallery',
        'preise'      => 'pricing',
        'anreise'     => 'arrival',
        'kontakt'     => 'contact',
    ];

    public function up(): void
    {
        foreach (self::MAP as $old => $new) {
            DB::table('template_sections')
                ->where('section_key', $old)
                ->update(['section_key' => $new]);
        }
    }

    public function down(): void
    {
        foreach (self::MAP as $old => $new) {
            DB::table('template_sections')
                ->where('section_key', $new)
                ->update(['section_key' => $old]);
        }
    }
};
