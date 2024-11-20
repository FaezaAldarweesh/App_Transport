<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory,softDeletes;
    /**
    * The attributes that are mass assignable.
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'type',
        'path_id',
        'status',
    ];

    public function buses (){
        
        return $this->belongsToMany(Bus::class);
    }

    public function students (){

        return $this->hasManyThrough(
            BusStudent::class,
            BusTrip::class,
            'trip_id',
            'bus_id',
            'id',
            'bus_id'
        );
    }
}
