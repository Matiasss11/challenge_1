<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_name' => data_get($this, 'customer_name', 'Guest'),
            'customer_email' => data_get($this, 'customer_email', 'N/A'),
            'tour_name' => data_get($this, 'tour.name', 'No Tour'),
            'hotel_name' => data_get($this, 'hotel.name', 'No Hotel'),
        ];
    }
}
