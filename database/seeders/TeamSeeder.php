<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Run seeder: php artisan db:seed --class=TeamSeeder
class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = (int) date('Y');

        foreach (range(1960, $currentYear) as $year) {
            Team::create([
                'name' => (string) $year,
                'organization_id' => 1,
            ]);
        }
    }
}
