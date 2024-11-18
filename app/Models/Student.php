<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory,softDeletes;

    /**
    * The attributes that are mass assignable.
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'father_phone',
        'mather_phone',
        'longitude',
        'latitude',
        'class_room_id',
        'user_id',
        'status',
    ];


    public function user (){
        
        return $this->belongsTo(User::class,'user_id','id');

    }

    public function classRoom (){
        
        return $this->belongsTo(ClassRoom::class,'class_room_id','id');

    }

    public function buses (){
        
        return $this->belongsToMany(Bus::class);
    }
}
