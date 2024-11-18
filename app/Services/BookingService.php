<?php

namespace App\Services;

use App\Enums\BookingStatusEnum;
use App\Jobs\ExportBookingsJob;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingService
{
    public function createBooking(array $data)
    {
        $booking = Booking::create($data);

        return $booking;
    }

    public function updateBooking(Booking $booking, array $data)
    {
        $booking->update($data);
        return $booking;
    }

    public function deleteBooking(Booking $booking)
    {
        $booking->delete();
    }

    public function getBookings(Request $request, int $perPage = 10): LengthAwarePaginator
    {
        $query = Booking::with(['tour', 'hotel']);

        $query->dateRange(
            $request->input('start_date'),
            $request->input('end_date')
        );

        $query->byTourName($request->input('tour_name'))
            ->byHotelName($request->input('hotel_name'))
            ->byCustomerName($request->input('customer_name'));

        if ($request->has('sort_by')) {
            $sortDirection = $request->input('sort_direction', 'asc');
            $query->orderBy($request->input('sort_by'), $sortDirection);
        }

        return $query->paginate($perPage);
    }

    public function getBooking(Booking $booking): Booking
    {
        return $booking;
    }

    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === BookingStatusEnum::CANCELED) {
            return ['message' => 'Booking is already canceled', 'code' => 400];
        }

        $booking->status = BookingStatusEnum::CANCELED;
        $booking->save();

        return ['message' => 'Booking canceled successfully', 'code' => 200];
    }

    public function exportBookings(array $filters): void
    {
        ExportBookingsJob::dispatch($filters);
    }
}
