<?php

namespace Database\Seeders;

use App\Helpers\MasterKeyHelper;
use App\Models\MasterKey;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Run seeder: php artisan db:seed --class=UserSeeder
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin 1',
            'email' => 'admin1@unsa.edu.pe',
            'password' => '12345678',
            'role' => 'admin',
        ]);

        $organization = User::factory()->create([
            'name' => 'Organization 1',
            'email' => 'organization1@unsa.edu.pe',
            'password' => '87654321',
            'role' => 'organization',
            'organization_id' => 1,
        ]);

        $supervisor = User::factory()->create([
            'name' => 'Supervisor 1',
            'email' => 'supervisor1@unsa.edu.pe',
            'password' => '12345678',
            'role' => 'supervisor',
            'organization_id' => 1,
        ]);

        $manager = User::factory()->create([
            'name' => 'Manager 1',
            'email' => 'manager1@unsa.edu.pe',
            'password' => '87654321',
            'role' => 'manager',
            'organization_id' => 1,
        ]);
    }
}
