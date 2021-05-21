<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoordinatesRequest;
use App\Models\Address;
use App\Models\Region;
use Illuminate\Support\Facades\Http;


class CoordinatesController extends Controller
{
    public function getCoords(CoordinatesRequest $request){
        if(isset($request->validator) && $request->validator->fails()){
            return response($request->validator->messages());
        }
        $coordinates = $request->latitude . ','.$request->longitude;
        $response = Http::get('http://api.positionstack.com/v1/reverse',[
            'access_key'=>'a4f186c7337e493177aa468244dc33b9',
            'query'=>$coordinates,
        ]);
        $arrayResponse = json_decode($response)->data[0];
        $id = $this->saveRegion($arrayResponse->region,$arrayResponse->locality);
        if(isset($id)) $this->saveCoordinates($coordinates,$arrayResponse->label, $id);
        return response()->json($arrayResponse);
    }

    public function saveCoordinates(string $coordinates, string $address,$id = 0){
        if(Address::where('coordinates',$coordinates)->first()){
             return false;
        }else{
            $new = new Address();
            $new->coordinates = $coordinates;
            $new->address = $address;
            $new->region_id = $id;
            $new->save();
        }
    }

    public function saveRegion(string $region, $city){
        if($reg = Region::where('region',$region)->first()){
            return $reg->id;
        }else{
            $new = new Region();
            $new->region = $region;
            if($city === null){
                $new->city = 'null';
            }else{
                $new->city = $city;
            }
            $new->save();
            return $new->id;
        }
    }

    public function getAddress($id = null){
        if(isset($id)&&!empty($id)){
            $address = Address::where('region_id',$id)->get();
            return response($address);
        }else{
            return response(Address::all());
        }
    }
}
