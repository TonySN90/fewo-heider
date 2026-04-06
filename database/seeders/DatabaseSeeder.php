<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(IconSeeder::class);
        $this->call(TemplateSeeder::class);
        $this->call(TenantSeeder::class);   // bindet currentTenant für nachfolgende Seeder
        $this->call(SeasonSeeder::class);
        $this->call(PricingNoteSeeder::class);
        $this->call(GallerySeeder::class);
    }
}
