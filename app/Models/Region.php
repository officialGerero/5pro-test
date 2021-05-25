<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'region',
    ];

    public function address(){
        return $this->belongsTo(Address::class,'region_id','id');
    }

    public function scopeRegionExists($query,string $region){
        return $query->where('region',$region)->first();
    }

    public function addRegion(string $region){
        $new = new Region();
        $new->region = $region;
        $new->save();
        return $new->id;
    }
}
