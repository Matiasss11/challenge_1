<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tour;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\User;

class BookingSearchTest extends TestCase
{
    use RefreshDatabase;

    protected $tour;
    protected $hotel;

    /** @test */
    public function it_can_search_bookings_by_tour_name_hotel_name_and_customer_name()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tour = Tour::factory()->create(['name' => 'Tour A']);
        $hotel = Hotel::factory()->create(['name' => 'Hotel A']);
        $booking1 = Booking::factory()->create([
            'tour_id' => $tour->id,
            'hotel_id' => $hotel->id,
            'customer_name' => 'John Doe',
        ]);

        // Create another booking that should not match the search
        Booking::factory()->create([
            'tour_id' => $tour->id,
            'hotel_id' => $hotel->id,
            'customer_name' => 'Jane Smith',
        ]);

        // Search
        $response = $this->getJson('/api/bookings?tour_name=Tour A&hotel_name=Hotel A&customer_name=John Doe');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
