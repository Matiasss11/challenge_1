<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Booking;

class BookingCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->markdown('emails.booking.created')
            ->subject('Your Booking Information')
            ->with([
                'clientName' => $this->booking->customer_name,
                'tourName' => $this->booking->tour->name,
                'hotelName' => $this->booking->hotel->name,
                'bookingDate' => $this->booking->created_at->format('d/m/Y'),
                'numberOfPeople' => $this->booking->number_of_people,
            ]);
    }
}
