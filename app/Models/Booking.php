<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'hotel_id',
        'customer_name',
        'customer_email',
        'number_of_people',
        'booking_date',
        'status'
    ];

    protected $casts = [
        'status' => BookingStatusEnum::class,
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    // Scope para buscar por nombre del tour
    public function scopeByTourName($query, $tourName)
    {
        if ($tourName) {
            return $query->whereHas('tour', function ($q) use ($tourName) {
                $q->where('name', 'like', "%{$tourName}%");
            });
        }
        return $query;
    }

    // Scope para buscar por nombre del hotel
    public function scopeByHotelName($query, $hotelName)
    {
        if ($hotelName) {
            return $query->whereHas('hotel', function ($q) use ($hotelName) {
                $q->where('name', 'like', "%{$hotelName}%");
            });
        }
        return $query;
    }

    // Scope para buscar por nombre del cliente
    public function scopeByCustomerName($query, $customerName)
    {
        if ($customerName) {
            return $query->where('customer_name', 'like', "%{$customerName}%");
        }
        return $query;
    }

    /**
     * Scope a query to filter bookings by date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $startDate
     * @param string|null $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, $startDate = null, $endDate = null)
    {
        if ($startDate) {
            $query->where('booking_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('booking_date', '<=', $endDate);
        }

        return $query;
    }
}
