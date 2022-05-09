<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Johnpaulmedina\Usps\Facades\Usps;


class Controller extends BaseController
{
    

    public function submitAddress(Request $request){

        
        $submittedAddress = [
            'Address' => $request->input('address'),
            'Apartment' => $request->input('apartment'), 
            'City' => $request->input('city'),
            'State' => $request->input('state'),
            'Zip' => $request->input('zip')
        ];

        
        $validateAddress = Usps::validate($submittedAddress);

        $validateAddress['original'] = $submittedAddress;

        return response()->json(
            $validateAddress
        );
    }

    public function saveAddress(Request $request){


        $submittedAddress = [
            'Address' => $request->input('address'),
            'Apartment' => $request->input('apartment'), 
            'City' => $request->input('city'),
            'State' => $request->input('state'),
            'Zip' => $request->input('zip')
        ];

        $createdAddressId = DB::table('Address')->insertGetId($submittedAddress);

        return response()->json(
            ['address' => $submittedAddress,
            'message' => 'Created Successfully']
        );


    }



};
