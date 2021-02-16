<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Validator;

class IdentityController extends Controller
{
    //
    public $successStatus = 200;

    public function getBvn(Request $request){
        $validator = Validator::make($request->all(),[
            'bvn'=>'required',
        ]);
        $bvn = $request['bvn'];
        if (Str::length($bvn) === 11 ){
            $response = Http::withHeaders([
                'Authorization'=> 'Bearer ' . env('Public_Key'),
                'Content-Length'=> 100,
            ])->post(env('API_BASE_URL','https://sandbox.wallets.africa/').'account/resolvebvn',[
                "bvn"=> $bvn,
                'SecretKey'=>env('Secret_Key', 'hfucj5jatq8h'),
            ]);
            if ($response->status() === 200){
                //save to wallet
                $data = $response->json();
                // $result['FirstName'] = $data['FirstName'];
                // $result['LastName'] = $data['LastName'];
                // $result['Email'] = $data['Email'];
                // $result['PhoneNumber'] = $data['PhoneNumber'];
                // $result['BVN'] = $data['BVN'];
                // $result['DateOfBirth'] = $data['DateOfBirth'];
                // $result['ResponseCode'] = $data['ResponseCode'];
                // $result['Message'] = $data['Message'];
                // $res = [
                //     'success'=> true,
                //     'data'=> $result,
                // ];
                return response()->json(
                [
                'FirstName'=>$data['FirstName'],
                'LastName'=>$data['LastName'],
                'Email'=>$data['Email'],
                'PhoneNumber'=>$data['PhoneNumber'],
                'BVN'=>$data['BVN'],
                'DateOfBirth'=>$data['DateOfBirth'],
                'ResponseCode'=>$data['ResponseCode'],
                'Message'=>$data['Message'],
                ]
                , $this->successStatus);
            }
        }else{
            return response()->json(['error'=>'Invalid BVN !!'], 400);
        }

        return $response->json();

    }


    public function getBvnFull(Request $request){
        $validator = Validator::make($request->all(),[
            'bvn'=>'required',
        ]);
        $bvn = $request['bvn'];
        if (Str::length($bvn) === 11 ){
            $response = Http::withHeaders([
                'Authorization'=> 'Bearer ' . env('Public_Key'),
                'Content-Length'=> 100,
            ])->post(env('API_BASE_URL','https://sandbox.wallets.africa/').'account/resolvebvn',[
                "bvn"=> $bvn,
                'SecretKey'=>env('Secret_Key', 'hfucj5jatq8h'),
            ]);
            if ($response->status() === 200){
                //save to wallet
            }
        }else{
            return response()->json(['error'=>'Invalid BVN !!'], 400);
        }

        return $response->json();

    }
}
