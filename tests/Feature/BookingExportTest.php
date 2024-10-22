<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\Hotel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingsExport;
use App\Models\User;

class BookingExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_export_bookings_to_csv()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Excel::fake();

        $tour = Tour::factory()->create();
        $hotel = Hotel::factory()->create();
        Booking::factory()->create(['tour_id' => $tour->id, 'hotel_id' => $hotel->id]);

        $response = $this->get('/api/bookings/export');

        $response->assertStatus(200);

        Excel::assertDownloaded('bookings.csv', function (BookingsExport $export) {
            return true;
        });
    }
}
