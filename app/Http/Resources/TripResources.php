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
            'trip name' => $this->name, 
            'trip type' => $this->type, 
            'trip path' => $this->path_id,
            'trip status' => $this->status == 0 ? 'trip end' : 'trip start',
            'buses' => $this->buses->map(function ($bus) {
                return [
                    'id' => $bus->id,
                    'bus name' => $bus->name,
                ];
            }),
        ];
    }
}
