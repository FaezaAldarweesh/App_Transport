<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory,softDeletes;

    /**
    * The attributes that are mass assignable.
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
    ];

    /**
    * Return a all grades with filter on name.
    * @param   $name
    * @return array
    */
    public function scopeFilter(Builder $query, $name)
    {
        if ($name !== null) {
            $query->where('name', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function classRooms (){
        
        return $this->hasMany(ClassRoom::class);

    }
}
