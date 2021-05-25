<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'address',
        'region_id'
    ];
    public $timestamps = false;

    public function region(){
        return $this->hasOne(Region::class,'id','region_id');
    }

    public function getAddress(){

    }

    public function saveAddress(string $latitude, string $longitude, string $address, int $id){
        $new = new Address();
        $new->latitude = $latitude;
        $new->longitude = $longitude;
        $new->address = $address;
        $new->region_id = $id;
        $new->save();
    }

    public function scopeWhereCoordsExist($query, $latitude, $longitude){
        return $query->where([
            ['latitude','=',$latitude],
            ['longitude','=',$longitude],
        ])->first();
    }

    public function scopeGetAddressByRegion($query, int $id){
        return $query->where('region_id',$id)->get();
    }

    public function getAllAddresses(){
        return Address::all();
    }
}
