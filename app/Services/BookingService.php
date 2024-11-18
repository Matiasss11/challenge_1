<?php

namespace App\Services;

use App\Enums\BookingStatusEnum;
use App\Mail\BookingCreatedMail;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class BookingService
{
    // Método para crear una nueva reserva
    public function createBooking(array $data)
    {
        $booking = Booking::create($data);

        // Enviar el correo de confirmación
        Mail::to($data['customer_email'])->send(new BookingCreatedMail($booking));

        return $booking;
    }

    // Método para actualizar una reserva existente
    public function updateBooking(Booking $booking, array $data)
    {
        $booking->update($data);
        return $booking;
    }

    // Método para eliminar una reserva
    public function deleteBooking(Booking $booking)
    {
        $booking->delete();
    }

    // Método para obtener reservas con filtros opcionales
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

    // Método para obtener una reserva
    public function getBooking(Booking $booking): Booking
    {
        return $booking;
    }

    // Método para cancelar una reserva
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
}
