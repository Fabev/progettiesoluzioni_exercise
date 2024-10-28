<?php

namespace Tests\Feature;

use App\Enumerations\FamilyRoles;
use App\Models\Citizen;
use App\Models\Family;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PromoteCitizenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_promoting(): void
    {
        $family = Family::factory()
            ->hasAttached(
                Citizen::factory()->create(),
                ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD), 'is_head' => true]
            )
            ->create();
        $citizen = Citizen::factory()->create();
        $family->citizens()->attach($citizen->id, ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD)]);

        $response = $this->post(route('family.promote', ['family' => $family->id]), ['citizen_id' => $citizen->id]);

        $response->assertStatus(200);
    }

    public function test_failing_promoting_for_invalid_role() {
        $family = Family::factory()
            ->hasAttached(
                Citizen::factory()->create(),
                ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD), 'is_head' => true]
            )
            ->create();
        $citizen = Citizen::factory()->create();
        $family->citizens()->attach($citizen->id, ['role' => FamilyRoles::CHILD->value]);

        $response = $this->post(route('family.promote', ['family' => $family->id]), ['citizen_id' => $citizen->id]);

        $response->assertStatus(400);
    }

    public function test_failing_promoting_for_invalid_family() {
        $family = Family::factory()
            ->hasAttached(
                Citizen::factory()->create(),
                ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD), 'is_head' => true]
            )
            ->create();
        $citizen = Citizen::factory()->create();
        $response = $this->post(route('family.promote', ['family' => $family->id]), ['citizen_id' => $citizen->id]);

        $response->assertStatus(400);
    }

    public function test_failing_promoting_for_invalid_citizen() {
        $family = Family::factory()
            ->hasAttached(
                Citizen::factory()->create(),
                ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD), 'is_head' => true]
            )
            ->create();
        $citizen = Citizen::factory()->create();
        $family->citizens()->attach($citizen->id, ['role' => FamilyRoles::PARENT->value]);

        for ($i = 0; $i < 3; $i++) {
            Family::factory()
                ->hasAttached(
                    $citizen,
                    ['role' => FamilyRoles::PARENT->value, 'is_head' => true]
                )
                ->create();
        }

        $response = $this->post(route('family.promote', ['family' => $family->id]), ['citizen_id' => $citizen->id]);

        $response->assertStatus(400);
    }
}
