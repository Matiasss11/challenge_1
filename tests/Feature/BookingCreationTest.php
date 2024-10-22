<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Models\Booking;
use App\Mail\BookingCreatedMail;
use App\Models\User;
use App\Models\Tour;
use App\Models\Hotel;

class BookingCreationTest extends TestCase
{
    use RefreshDatabase;

    protected $tour;
    protected $hotel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tour = Tour::create([
            'name' => 'Tour Example',
            'description' => 'Description of the tour',
            'price' => 100,
            'start_date' => now(),
            'end_date' => now()->addDays(5),
        ]);

        $this->hotel = Hotel::create([
            'name' => 'Hotel Example',
            'description' => 'Description of the hotel',
            'address' => '123 Hotel St.',
            'rating' => 5,
            'price_per_night' => 150,
        ]);
    }

    /** @test */
    public function it_sends_an_email_when_a_booking_is_created()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Mail::fake();

        $bookingData = [
            'tour_id' => $this->tour->id,
            'hotel_id' => $this->hotel->id,
            'customer_name' => 'John Doe',
            'customer_email' => 'john.doe@example.com',
            'number_of_people' => 4,
            'booking_date' => now()->toDateString(),
        ];

        $response = $this->postJson('/api/bookings', $bookingData);

        $response->assertStatus(201);

        Mail::assertQueued(BookingCreatedMail::class, function ($mail) use ($bookingData) {
            return $mail->booking->customer_email === $bookingData['customer_email'];
        });

        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john.doe@example.com',
            'number_of_people' => 4,
        ]);
    }
}
