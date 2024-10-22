<?php

namespace App\Observers;

use App\Models\Booking;
use App\Mail\BookingCreatedMail;
use Illuminate\Support\Facades\Mail;

class BookingObserver
{
    public function created(Booking $booking)
    {
        Mail::to($booking->customer_email)->send(new BookingCreatedMail($booking));
    }
}
