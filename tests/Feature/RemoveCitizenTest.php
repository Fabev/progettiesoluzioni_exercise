<?php

namespace Tests\Feature;

use App\Enumerations\FamilyRoles;
use App\Models\Citizen;
use App\Models\Family;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RemoveCitizenTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_remove(): void
    {
        $family = Family::factory()
            ->hasAttached(
                Citizen::factory()->create(),
                ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD), 'is_head' => true]
            )
            ->create();
        $citizen = Citizen::factory()->create();
        $family->citizens()->attach($citizen->id, ['role' => FamilyRoles::getRandom()]);
        $response = $this->delete(route('families.citizens.remove', ['family' => $family->id, 'citizen' => $citizen->id]));
        $response->assertStatus(200);
    }

    public function test_failing_removing_head() {
        $family = Family::factory()
            ->create();
        $citizen = Citizen::factory()->create();
        $family->citizens()->attach($citizen->id, ['role' => FamilyRoles::getRandom(FamilyRoles::CHILD), 'is_head' => true]);
        $response = $this->delete(route('families.citizens.remove', ['family' => $family->id, 'citizen' => $citizen->id]));
        $response->assertStatus(400);
    }
}
