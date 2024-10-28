<?php

namespace Tests\Feature;

use App\Enumerations\FamilyRoles;
use App\Models\Citizen;
use App\Models\Family;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JoinFamilyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_join(): void
    {
        $family = Family::factory()
            ->hasAttached(
                Citizen::factory()->create(),
                ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD), 'is_head' => true]
            )
            ->create();
        $citizen = Citizen::factory()->create();
        $response = $this->post(route('families.citizens.join', ['family' => $family->id]), [
            'citizen_id' => $citizen->id,
            'role' => FamilyRoles::getRandom(),
        ]);

        $response->assertStatus(200);
    }

    public function test_failing_join_full_family() {
        $family = Family::factory()
            ->hasAttached(
                Citizen::factory()->create(),
                ['role' => FamilyRoles::PARENT->value, 'is_head' => true]
            )
            ->create();
        for ($i = 0; $i < 5; $i++) {
            $c = Citizen::factory()->create();
            $family->citizens()->attach($c->id, ['role' => FamilyRoles::getRandom(), 'is_head' => false]);
        }

        $citizen = Citizen::factory()->create();
        $response = $this->post(route('families.citizens.join', ['family' => $family->id]), [
            'citizen_id' => $citizen->id,
            'role' => FamilyRoles::getRandom(),
        ]);

        $response->assertStatus(400);
    }
}
