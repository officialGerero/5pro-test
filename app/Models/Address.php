<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'coordinates',
        'address',
        'region_id'
    ];
    public $timestamps = false;

    public function region(){
        return $this->hasOne(Region::class,'id','region_id');
    }
}
