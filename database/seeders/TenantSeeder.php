<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $template = Template::where('slug', 'fewo-01')->first();

        $tenant = Tenant::firstOrCreate(
            ['domain' => 'muster-fewo.de'],
            [
                'name' => 'Musterferienwohnung',
                'template_id' => $template?->id,
                'is_active' => true,
            ]
        );

        // Als aktuelle Instanz setzen, damit nachfolgende Seeder den Scope nutzen
        app()->instance('currentTenant', $tenant);

        // Backfill bestehender Daten ohne tenant_id
        DB::table('bookings')->whereNull('tenant_id')->update(['tenant_id' => $tenant->id]);
        DB::table('seasons')->whereNull('tenant_id')->update(['tenant_id' => $tenant->id]);
        DB::table('pricing_notes')->whereNull('tenant_id')->update(['tenant_id' => $tenant->id]);

        // Client-User der Instanz zuordnen
        $client = User::where('email', 'client@co-ding.de')->first();
        if ($client && ! $tenant->users()->where('users.id', $client->id)->exists()) {
            $tenant->users()->attach($client->id);
        }

        // Tenant-eigene Sektionskopien anlegen
        if ($template) {
            $template->ensureTenantSections($tenant);
        }
    }
}
