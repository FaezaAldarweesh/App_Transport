<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student id' => $this->id,
            'student name' => $this->name, 
            'student father phone' => $this->father_phone,
            'student mather phone' => $this->mather_phone,
            'student location' => $this->location,
            'student grade' => $this->classRoom->grade->name,
            'student class' => $this->classRoom->name,
            'student parent' => $this->user->name,
        ];
    }
}
