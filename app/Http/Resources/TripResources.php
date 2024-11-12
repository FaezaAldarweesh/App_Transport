<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'trip id' => $this->id,
            'trip type' => $this->type, 
            'trip bus' => $this->bus_id,
            'trip path' => $this->path_id,
        ];
    }
}
