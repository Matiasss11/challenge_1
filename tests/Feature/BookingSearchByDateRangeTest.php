<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tour;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\User;

class BookingSearchByDateRangeTest extends TestCase
{
    use RefreshDatabase;

    protected $tour;
    protected $hotel;

    /** @test */
    public function it_can_search_bookings_by_date_range()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tour = Tour::factory()->create(['name' => 'Adventure Tour']);
        $hotel = Hotel::factory()->create(['name' => 'Luxury Resort']);

        // Create bookings with different dates
        Booking::factory()->create([
            'tour_id' => $tour->id,
            'hotel_id' => $hotel->id,
            'customer_name' => 'John Doe',
            'booking_date' => now()->toDateString(), // Today date
        ]);

        Booking::factory()->create([
            'tour_id' => $tour->id,
            'hotel_id' => $hotel->id,
            'customer_name' => 'Jane Smith',
            'booking_date' => now()->addDays(10)->toDateString(), // Within range
        ]);

        Booking::factory()->create([
            'tour_id' => $tour->id,
            'hotel_id' => $hotel->id,
            'customer_name' => 'Tom Brown',
            'booking_date' => now()->addDays(20)->toDateString(), // Out of range
        ]);

        $response = $this->getJson('/api/bookings?start_date=' . now()->toDateString() . '&end_date=' . now()->addDays(15)->toDateString());

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['customer_name' => 'John Doe'])
            ->assertJsonFragment(['customer_name' => 'Jane Smith']);
    }
}
