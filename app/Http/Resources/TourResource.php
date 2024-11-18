<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
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
            'price' => data_get($this, 'price', 0.0),
        ];
    }
}
