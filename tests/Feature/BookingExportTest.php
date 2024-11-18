<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\Hotel;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ExportBookingsJob;
use App\Models\User;

class BookingExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_export_bookings_to_csv()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Bus::fake();

        $tour = Tour::factory()->create();
        $hotel = Hotel::factory()->create();
        Booking::factory()->create(['tour_id' => $tour->id, 'hotel_id' => $hotel->id]);

        $response = $this->get('/api/bookings/export');

        $response->assertStatus(202);

        Bus::assertDispatched(ExportBookingsJob::class, function ($job) {
            return true;
        });
    }
}
