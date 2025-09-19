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
            'email' => 'admin@gmail.com',
            'password' => '12345678',
            'role' => 'admin',
        ]);

        $organization = User::factory()->create([
            'name' => 'Padre Francois Delatte',
            'email' => 'padre.francois.delatte@gmail.com',
            'password' => '87654321',
            'role' => 'organization',
            'organization_id' => 1,
        ]);

        $supervisor = User::factory()->create([
            'name' => 'Zenaida Porroa Huayna',
            'email' => 'tvzenaida@gmail.com',
            'password' => 'controlEventoFrancois25',
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

        $controller = User::factory()->create([
            'name' => 'Controlador',
            'email' => 'controller@gmail.com',
            'password' => '87654321',
            'role' => 'controller',
            'organization_id' => 1,
        ]);
    }
}
