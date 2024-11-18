<?php

namespace App\Services;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TourService
{
    public function getTours(Request $request, int $perPage = 10): LengthAwarePaginator
    {
        $query = Tour::query();

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->has('start_date')) {
            $query->whereDate('start_date', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->whereDate('end_date', '<=', $request->input('end_date'));
        }

        return $query->paginate($perPage);
    }

    public function createTour(array $data): Tour
    {
        return Tour::create($data);
    }

    public function getTour(Tour $tour): Tour
    {
        return $tour;
    }

    public function updateTour(Tour $tour, array $data): Tour
    {
        $tour->update($data);
        return $tour;
    }

    public function deleteTour(Tour $tour): void
    {
        $tour->delete();
    }
}
