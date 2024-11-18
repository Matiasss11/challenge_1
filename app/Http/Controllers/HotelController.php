<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Services\HotelService;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Http\Resources\HotelResource;

class HotelController extends Controller
{
    protected $hotelService;

    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $hotels = $this->hotelService->getHotels($request, $perPage);

        return response()->json($hotels, 200);
    }

    public function store(StoreHotelRequest $request)
    {
        $validatedData = $request->validated();
        $hotel = $this->hotelService->createHotel($validatedData);

        return response()->json(new HotelResource($hotel), 201);
    }

    public function show(Hotel $hotel)
    {
        $hotelData = $this->hotelService->getHotel($hotel);
        return new HotelResource($hotelData);
    }

    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        $validatedData = $request->validated();
        $updatedHotel = $this->hotelService->updateHotel($hotel, $validatedData);
        return response()->json($updatedHotel, 200);
    }

    public function destroy(Hotel $hotel)
    {
        $this->hotelService->deleteHotel($hotel);
        return response()->json(null, 204);
    }
}
