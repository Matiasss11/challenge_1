@component('mail::message')
    # Booking Confirmation

    Hello {{ $clientName }},

    Thank you for booking a tour with us! Here are your booking details:

    - **Tour Name**: {{ $tourName }}
    - **Hotel Name**: {{ $hotelName }}
    - **Booking Date**: {{ $bookingDate }}
    - **Number of People**: {{ $numberOfPeople }}

    We look forward to seeing you!

    Thanks,
    {{ config('app.name') }}
@endcomponent
