<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use HasFactory,softDeletes;
    /**
    * The attributes that are mass assignable.
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'grade_id',
    ];

    public function grade (){
        
        return $this->belongsTo(Grade::class,'grade_id','id');

    }

    public function students (){
        
        return $this->hasMany(Student::class);

    }
}
