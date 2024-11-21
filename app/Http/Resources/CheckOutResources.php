<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckOutResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Check out id' => $this->id,
            'Check out student' => $this->student_id,
            'Check out trip' => $this->trip_id, 
            'Check out' => $this->check_out,
            'Check out note' => $this->note,
        ];
    }
}
