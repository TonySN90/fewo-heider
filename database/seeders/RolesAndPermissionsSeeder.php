<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view bookings',
            'manage bookings',
            'manage seasons',
            'manage pricing',
            'manage templates',
            'manage gallery',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions($permissions);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        $client = Role::firstOrCreate(['name' => 'client']);
        $client->syncPermissions(['view bookings', 'manage bookings', 'manage seasons', 'manage pricing']);

        // tony@co-ding.de → super-admin
        $user = User::where('email', 'tony@co-ding.de')->first();
        if ($user) {
            $user->syncRoles(['super-admin']);
        }
    }
}
