<?php

namespace App\Services;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HotelService
{
    public function getHotels(Request $request, int $perPage = 10): LengthAwarePaginator
    {
        $query = Hotel::query();

        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->input('min_rating'));
        }

        if ($request->has('max_rating')) {
            $query->where('rating', '<=', $request->input('max_rating'));
        }

        if ($request->has('min_price')) {
            $query->where('price_per_night', '>=', $request->input('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('price_per_night', '<=', $request->input('max_price'));
        }

        return $query->paginate($perPage);
    }

    public function createHotel(array $data): Hotel
    {
        return Hotel::create($data);
    }

    public function getHotel(Hotel $hotel): Hotel
    {
        return $hotel;
    }

    public function updateHotel(Hotel $hotel, array $data): Hotel
    {
        $hotel->update($data);
        return $hotel;
    }

    public function deleteHotel(Hotel $hotel): void
    {
        $hotel->delete();
    }
}
