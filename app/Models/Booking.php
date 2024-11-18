<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
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

    /**
     * Scope a query to filter bookings by tour name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $tourName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTourName($query, ?string $tourName): Builder
    {
        if (is_null($tourName)) {
            return $query;
        }

        return $query->whereHas('tour', function ($q) use ($tourName) {
            $q->where('name', 'like', "%{$tourName}%");
        });
    }

    /**
     * Scope a query to filter bookings by hotel name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $hotelName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByHotelName($query, ?string $hotelName): Builder
    {
        if (is_null($hotelName)) {
            return $query;
        }

        return $query->whereHas('hotel', function ($q) use ($hotelName) {
            $q->where('name', 'like', "%{$hotelName}%");
        });
    }

    /**
     * Scope a query to filter bookings by customer name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $customerName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCustomerName($query, ?string $customerName): Builder
    {
        if (is_null($customerName)) {
            return $query;
        }

        return $query->where('customer_name', 'like', "%{$customerName}%");
    }

    /**
     * Scope a query to filter bookings by date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $startDate
     * @param string|null $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, ?string $startDate, ?string $endDate): Builder
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
