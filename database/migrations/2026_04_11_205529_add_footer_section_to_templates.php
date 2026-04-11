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
            $global = TemplateSection::firstOrCreate(
                ['template_id' => $template->id, 'section_key' => 'footer', 'tenant_id' => null],
                ['is_visible' => true, 'sort_order' => 99]
            );

            foreach (Tenant::where('template_id', $template->id)->get() as $tenant) {
                TemplateSection::firstOrCreate(
                    ['template_id' => $template->id, 'section_key' => 'footer', 'tenant_id' => $tenant->id],
                    ['is_visible' => true, 'sort_order' => 99]
                );
            }
        }
    }

    public function down(): void
    {
        TemplateSection::where('section_key', 'footer')->delete();
    }
};
