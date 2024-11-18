<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Bus extends Model
{
    use HasFactory,softDeletes;

    /**
    * The attributes that are mass assignable.
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'number_of_seats',
    ];

        public function students (){
            
            return $this->belongsToMany(Student::class);
        }

    public function supervisors (){
        
        return $this->belongsToMany(Supervisor::class);
    }

    public function drivers (){
        
        return $this->belongsToMany(Driver::class);
    }

    public function trips (){
        
        return $this->belongsToMany(Trip::class);
    }
}
