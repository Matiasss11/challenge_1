<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingPaginationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_paginate_bookings()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Booking::factory()->count(25)->create();

        $response = $this->getJson('/api/bookings?per_page=10');

        $response->assertStatus(200);

        $this->assertCount(10, $response->json()['data']);

        $this->assertArrayHasKey('current_page', $response->json());
        $this->assertArrayHasKey('last_page', $response->json());
        $this->assertArrayHasKey('per_page', $response->json());
        $this->assertArrayHasKey('total', $response->json());

        $this->assertEquals(25, $response->json()['total']);
    }
}
