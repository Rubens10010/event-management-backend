<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Run seeder: php artisan db:seed --class=OrganizationSeeder
class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event = Event::create([
            'organization_id' => 1,
            'name' => 'Reencuentro de Promociones',
            'description' => 'Evento para el reencuentro de las promociones de egresados.',
            'location' => 'Club La Campiña',
            'capacity' => 200,
            'max_invitees' => 5,
            'starts_at' => '2025-12-20 19:00:00',
            'finishes_at' => '2025-12-20 23:00:00',
            'status' => 'REGISTERING'
        ]);
    }
}
