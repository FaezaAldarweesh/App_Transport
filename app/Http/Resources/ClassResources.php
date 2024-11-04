<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'class id' => $this->id,
            'class name' => $this->name, 
            'class grade' => $this->grade ? $this->grade->name : null,
        ];
    }
}
