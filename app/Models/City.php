<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function addCity(string $city){
        $new = new City();
        $new->name = $city;
        $new->save();
    }

    public function scopeCityExists($query, string $city){
        return $query->where('name',$city)->first();
    }
}
