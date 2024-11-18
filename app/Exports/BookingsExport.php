<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingsExport implements FromQuery, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $bookings = Booking::with(['tour', 'hotel'])
            ->select([
                'id',
                'customer_name',
                'customer_email',
                'number_of_people',
                'booking_date',
                'tour_id',
                'hotel_id'
            ]);

        if (!empty($this->filters['start_date']) || !empty($this->filters['end_date'])) {
            $bookings->dateRange($this->filters['start_date'] ?? null, $this->filters['end_date'] ?? null);
        }

        if (!empty($this->filters['tour_name'])) {
            $bookings->byTourName($this->filters['tour_name']);
        }

        if (!empty($this->filters['hotel_name'])) {
            $bookings->byHotelName($this->filters['hotel_name']);
        }

        if (!empty($this->filters['customer_name'])) {
            $bookings->byCustomerName($this->filters['customer_name']);
        }

        if (!empty($this->filters['sort_by'])) {
            $sortDirection = $this->filters['sort_direction'] ?? 'asc';
            $bookings->orderBy($this->filters['sort_by'], $sortDirection);
        }

        // Get the results
        return $bookings->get()->map(function ($booking) {
            return [
                'id' => $booking->id,
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'number_of_people' => $booking->number_of_people,
                'booking_date' => $booking->booking_date,
                'tour_name' => $booking->tour->name ?? 'N/A',
                'hotel_name' => $booking->hotel->name ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Customer Name',
            'Customer Email',
            'Number of People',
            'Booking Date',
            'Tour Name',
            'Hotel Name'
        ];
    }
}
