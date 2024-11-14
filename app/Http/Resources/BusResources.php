<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'bus id' => $this->id,
            'bus name' => $this->name, 
            'number_of_seats' => $this->number_of_seats,
            'students' => $this->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'student name' => $student->name,
                ];
            }),
            'supervisors' => $this->supervisors->map(function ($supervisor) {
                return [
                    'id' => $supervisor->id,
                    'supervisor name' => $supervisor->name,
                ];
            }),
            'drivers' => $this->drivers->map(function ($driver) {
                return [
                    'id' => $driver->id,
                    'driver name' => $driver->name,
                ];
            }),
        ];
    }
}
