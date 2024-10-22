<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Hotel;
use App\Models\User;

class HotelPaginationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_paginate_hotels()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Hotel::factory()->count(30)->create();

        $response = $this->getJson('/api/hotels?per_page=15');

        $response->assertStatus(200);
        $this->assertCount(15, $response->json()['data']);
        $this->assertEquals(30, $response->json()['total']);
    }
}
