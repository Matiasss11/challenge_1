<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Mail\BookingCreatedMail;
use App\Models\Booking;
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
    public function getBookings(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Booking::with(['tour', 'hotel']);

        if (!empty($filters['start_date']) || !empty($filters['end_date'])) {
            $query->dateRange($filters['start_date'] ?? null, $filters['end_date'] ?? null);
        }

        if (!empty($filters['tour_name'])) {
            $query->byTourName($filters['tour_name']);
        }

        if (!empty($filters['hotel_name'])) {
            $query->byHotelName($filters['hotel_name']);
        }

        if (!empty($filters['customer_name'])) {
            $query->byCustomerName($filters['customer_name']);
        }

        if (!empty($filters['sort_by'])) {
            $sortDirection = $filters['sort_direction'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $sortDirection);
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

        if ($booking->status === BookingStatus::Canceled) {
            return ['message' => 'Booking is already canceled', 'code' => 400];
        }

        $booking->status = BookingStatus::Canceled;
        $booking->save();

        return ['message' => 'Booking canceled successfully', 'code' => 200];
    }
}
