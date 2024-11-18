<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => data_get($this, 'id'),
            'name' => data_get($this, 'name', 'No Name'),
            'description' => data_get($this, 'description', 'No Description'),
            'address' => data_get($this, 'address', 'No Address'),
            'rating' => data_get($this, 'rating', 0),
            'price_per_night' => data_get($this, 'price_per_night', 0.0),
        ];
    }
}
