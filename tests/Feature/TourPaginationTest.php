<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tour;
use App\Models\User;

class TourPaginationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_paginate_tours()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Tour::factory()->count(25)->create();

        $response = $this->getJson('/api/tours?per_page=10');

        $response->assertStatus(200);
        $this->assertCount(10, $response->json()['data']);
        $this->assertEquals(25, $response->json()['total']);
    }
}
