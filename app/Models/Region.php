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
        'city',
    ];

    public function address(){
        return $this->belongsTo(Address::class,'region_id','id');
    }
}
