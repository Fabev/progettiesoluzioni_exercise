<?php

namespace Database\Seeders;

use App\Enumerations\FamilyRoles;
use App\Models\Citizen;
use App\Models\Family;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Family::factory()
            ->count(10)
            ->create()
            ->each(function (Family $family) {
                $citizens = array();
                for ($i = 0; $i < rand(1, 5); $i++) {   // max is 6 = head + 5 members
                    $citizen = Citizen::factory()->create();
                    $citizens[$citizen->id] = ['role' => FamilyRoles::getRandom()];
                }
                $family->citizens()->attach($citizens);
            });
    }
}
