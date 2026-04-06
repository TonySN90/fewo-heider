<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    /** @return HasMany<TemplateSection, $this> */
    public function sections(): HasMany
    {
        return $this->hasMany(TemplateSection::class)
            ->whereNull('tenant_id')
            ->orderBy('sort_order');
    }

    /**
     * Tenant-eigene Sektionen
     * @return Builder<TemplateSection>
     */
    public function sectionsForTenant(int $tenantId): Builder
    {
        return TemplateSection::where('template_id', $this->id)
            ->where('tenant_id', $tenantId)
            ->orderBy('sort_order');
    }

    public function findSection(string $sectionKey): TemplateSection
    {
        /** @var TemplateSection */
        return $this->sections()->where('section_key', $sectionKey)->firstOrFail();
    }

    public function findTenantSection(string $sectionKey, int $tenantId): TemplateSection
    {
        return TemplateSection::where('template_id', $this->id)
            ->where('section_key', $sectionKey)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();
    }

    public function getSection(string $sectionKey): ?TemplateSection
    {
        /** @var TemplateSection|null */
        return $this->sections->firstWhere('section_key', $sectionKey);
    }

    /** Kopiert globale Sektionen + Content als Tenant-Kopie (idempotent) */
    public function ensureTenantSections(Tenant $tenant): void
    {
        $globalSections = $this->sections()->with('content')->get();

        foreach ($globalSections as $global) {
            $tenantSection = TemplateSection::firstOrCreate(
                [
                    'template_id' => $this->id,
                    'section_key' => $global->section_key,
                    'tenant_id'   => $tenant->id,
                ],
                [
                    'is_visible' => $global->is_visible,
                    'sort_order' => $global->sort_order,
                ]
            );

            foreach ($global->content as $item) {
                TemplateSectionContent::firstOrCreate(
                    [
                        'template_section_id' => $tenantSection->id,
                        'field_key'           => $item->field_key,
                    ],
                    ['value' => $item->value]
                );
            }
        }
    }

    public function activate(): void
    {
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }

    public static function active(): ?self
    {
        return static::with('sections')->where('is_active', true)->first();
    }
}
