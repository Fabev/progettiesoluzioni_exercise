<?php

namespace Database\Factories;

use App\Enumerations\FamilyRoles;
use App\Models\Citizen;
use App\Models\Family;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Family>
 */
class FamilyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'head_citizen_id' => Citizen::factory()
        ];
    }

    public function configure() {
        return $this->afterCreating(function (Family $family) {
            // attach head citizen with random role
            // removing child role from array to avoid children being head citizens
            $family->citizens()->attach($family->head_citizen_id, [
                'role' => FamilyRoles::getRandom(FamilyRoles::CHILD)
            ]);
        });
    }
}
