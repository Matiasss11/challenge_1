<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\BookingService;
use App\Http\Resources\BookingResource;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $bookings = $this->bookingService->getBookings($request, $perPage);

        return response()->json($bookings, 200);
    }

    public function store(StoreBookingRequest $request)
    {
        $booking = $this->bookingService->createBooking($request->validated());
        return response()->json($booking, 201);
    }

    public function show(Booking $booking)
    {
        return new BookingResource($this->bookingService->getBooking($booking));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $updatedBooking = $this->bookingService->updateBooking($booking, $request->validated());
        return response()->json($updatedBooking, 200);
    }

    public function destroy(Booking $booking)
    {
        $this->bookingService->deleteBooking($booking);
        return response()->json(null, 204);
    }

    public function export(Request $request)
    {
        $filters = $request->only([
            'start_date',
            'end_date',
            'tour_name',
            'hotel_name',
            'customer_name',
            'sort_by',
            'sort_direction'
        ]);

        $this->bookingService->exportBookings($filters);

        return response()->json([
            'message' => 'The booking export process has been initiated'
        ], 202);
    }

    public function cancel($id)
    {
        $result = $this->bookingService->cancelBooking($id);

        return response()->json(['message' => $result['message']], $result['code']);
    }
}
