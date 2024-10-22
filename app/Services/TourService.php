<?php

namespace App\Services;

use App\Models\Tour;
use Illuminate\Pagination\LengthAwarePaginator;

class TourService
{
    public function getTours(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Tour::query();

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('start_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('end_date', '<=', $filters['end_date']);
        }

        return $query->paginate($perPage);
    }

    public function createTour(array $data): Tour
    {
        return Tour::create($data);
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
