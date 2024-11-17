<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Booking;
use App\Enums\BookingStatusEnum;
use App\Models\User;

class BookingCancelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_cancel_a_booking()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $booking = Booking::factory()->create([
            'status' => BookingStatusEnum::PENDING
        ]);

        $response = $this->patchJson(route('bookings.cancel', $booking->id));

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Booking canceled successfully']);

        $this->assertEquals(BookingStatusEnum::CANCELED, $booking->fresh()->status);
    }

    /** @test */
    public function it_does_not_cancel_an_already_canceled_booking()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $booking = Booking::factory()->create([
            'status' => BookingStatusEnum::CANCELED
        ]);

        $response = $this->patchJson(route('bookings.cancel', $booking->id));

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Booking is already canceled']);
    }
}
