<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoordinatesRequest;
use App\Models\Address;
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
        $arrayResponse = json_decode($response);
        $this->saveCoordinates($coordinates,$arrayResponse->data[0]->label);
        return response($response);
    }

    public function saveCoordinates(string $coordinates, string $address){
        $new = new Address();
        $new->coordinates = $coordinates;
        $new->address = $address;
        $new->save();
    }
}
