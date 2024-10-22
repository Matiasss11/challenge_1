<?php

namespace App\Services;

use App\Models\Hotel;
use Illuminate\Pagination\LengthAwarePaginator;

class HotelService
{
    // Método para obtener hoteles con filtros opcionales
    public function getHotels(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Hotel::query();

        // Filtrado por criterios de búsqueda
        if (isset($filters['min_rating'])) {
            $query->where('rating', '>=', $filters['min_rating']);
        }

        if (isset($filters['max_rating'])) {
            $query->where('rating', '<=', $filters['max_rating']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price_per_night', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price_per_night', '<=', $filters['max_price']);
        }

        return $query->paginate($perPage);
    }

    // Método para crear un nuevo hotel
    public function createHotel(array $data): Hotel
    {
        return Hotel::create($data);
    }

    // Método para obtener un hotel específico
    public function getHotel(Hotel $hotel): Hotel
    {
        return $hotel;
    }

    // Método para actualizar un hotel
    public function updateHotel(Hotel $hotel, array $data): Hotel
    {
        $hotel->update($data);
        return $hotel;
    }

    // Método para eliminar un hotel
    public function deleteHotel(Hotel $hotel): void
    {
        $hotel->delete();
    }
}
