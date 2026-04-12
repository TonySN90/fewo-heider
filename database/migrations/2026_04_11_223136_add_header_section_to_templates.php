<?php

use App\Models\Template;
use App\Models\TemplateSection;
use App\Models\Tenant;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        foreach (Template::all() as $template) {
            TemplateSection::firstOrCreate(
                ['template_id' => $template->id, 'section_key' => 'header', 'tenant_id' => null],
                ['is_visible' => true, 'sort_order' => 0]
            );

            foreach (Tenant::where('template_id', $template->id)->get() as $tenant) {
                TemplateSection::firstOrCreate(
                    ['template_id' => $template->id, 'section_key' => 'header', 'tenant_id' => $tenant->id],
                    ['is_visible' => true, 'sort_order' => 0]
                );
            }
        }
    }

    public function down(): void
    {
        TemplateSection::where('section_key', 'header')->delete();
    }
};