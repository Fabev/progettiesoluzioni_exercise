<?php

namespace Tests\Feature;

use App\Enumerations\FamilyRoles;
use App\Models\Citizen;
use App\Models\Family;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoveCitizenTest extends TestCase {

    /**
     * A basic feature test example.
     */
    public function test_move(): void {
        $fromFamily = Family::factory()
            ->hasAttached(
                Citizen::factory()->create(),
                ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD), 'is_head' => true]
            )
            ->create();
        $citizen = Citizen::factory()->create();
        $fromFamily->citizens()->attach($citizen->id, ['role' => FamilyRoles::getRandom()]);
        $toFamily = Family::factory()
            ->hasAttached(
                Citizen::factory()->create(),
                ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD), 'is_head' => true]
            )
            ->create();
        $response = $this->post(route('citizens.move', ['citizen' => $citizen->id]), ['family_from_id' => $fromFamily->id, 'family_to_id' => $toFamily->id]);
        $response->assertStatus(200);
    }

    public function test_failing_moving_head() {
        $fromFamily = Family::factory()
            ->create();
        $citizen = Citizen::factory()->create();
        $fromFamily->citizens()->attach($citizen->id, ['role' => FamilyRoles::getRandom(), 'is_head' => true]);
        $toFamily = Family::factory()
            ->hasAttached(
                Citizen::factory()->create(),
                ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD), 'is_head' => true]
            )
            ->create();
        $response = $this->post(route('citizens.move', ['citizen' => $citizen->id]), ['family_from_id' => $fromFamily->id, 'family_to_id' => $toFamily->id]);
        $response->assertStatus(400);
    }
}
