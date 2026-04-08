<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'gast@fewo-heider.de'],
            [
                'name' => 'Gast',
                'password' => Hash::make('Gast-Password-2026!'),
            ]
        );

        if (! $user->hasRole('client')) {
            $user->assignRole('client');
        }
    }
}
