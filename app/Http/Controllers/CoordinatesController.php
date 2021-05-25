<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoordinatesRequest;
use App\Models\Address;
use App\Models\City;
use App\Models\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;


class CoordinatesController extends Controller
{
    private $address,$region,$city;

    public function __construct(Address $address, Region $region, City $city)
    {
        $this->address = $address;
        $this->region = $region;
        $this->city = $city;
    }

    public function getCoords(CoordinatesRequest $request){
        if(isset($request->validator) && $request->validator->fails()){
            return response($request->validator->messages());
        }
        $response = Http::get('http://api.positionstack.com/v1/reverse',[
            'access_key'=>'a4f186c7337e493177aa468244dc33b9',
            'query'=>$request->latitude . ','.$request->longitude,
        ]);
        $arrayResponse = json_decode($response)->data[0];
        $id = $this->saveRegion($arrayResponse->region);
        $this->saveCity($arrayResponse->locality);
        if(isset($id)) $this->saveAddress($request->latitude,$request->longitude,$arrayResponse->label, $id);
        return response()->json($arrayResponse);
    }

    private function saveAddress(string $latitude,string $longitude, string $address,$id = 0){
        if(isset($this->address->WhereCoordsExist($latitude,$longitude)->id)){
            return false;
        }else{
            $this->address->saveAddress($latitude,$longitude,$address,$id);
        }
    }

    private function saveRegion(string $region){
        $reg = $this->region->RegionExists($region);
        if(isset($reg) && !empty($reg->id)){
            return $reg->id;
        }else{
            return $this->region->addRegion($region);
        }
    }

    private function saveCity($city){
        $test = $this->city->CityExists($city);
        if (isset($test->name)){
            return false;
        }else {
            if ($city === null) {
                $city = 'null';
            }
            $this->city->addCity($city);
        }
    }

    public function getAddress($id = null){
        if(isset($id)&&!empty($id)){
            return response($this->address->GetAddressByRegion($id));
        }else{
            return response($this->address->getAllAddresses());
        }
    }
}
