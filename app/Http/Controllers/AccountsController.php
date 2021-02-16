<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use illuminate\Support\Str;
use Validator;


class AccountsController extends Controller
{
    //
    public function getBalance(Request $request){
       $validator = Validator::make($request->all(),[
           'currency'=>'required'
       ]);

       if ($validator->fails()){
        return response()->json(['error'=>$validator->errors()], 401);
    }
       $currency = $request['currency'];
        $response = Http::withHeaders(
            [
                'Authorization'=> 'Bearer ' . env('Public_Key'),
            ]
        )->post(env('API_BASE_URL','https://sandbox.wallets.africa/').'self/balance',[
            'Currency'=> $currency,
            'SecretKey'=>env('Secret_Key', 'hfucj5jatq8h'),
        ]);

        return $response->json();
    }


    public function getTransactions(Request $request){
        $validator = Validator::make($request->all(),[
            'currency'=>'required',
            'transactionType'=>'required',
            'dateFrom'=>'required',
            'dateTo'=>'required',
            'skip'=>'required',
            'take'=>'required',
        ]);
        
        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $currency = $request['currency'];
        $skip = $request['skip'];
        $take = $request['take'];
        $dateFrom = $request['dateFrom'];
        $dateTo= $request['dateTo'];
        $tType= $request['transactionType'];
         $response = Http::withHeaders(
             [
                 'Authorization'=> 'Bearer ' . env('Public_Key'),
             ]
         )->post(env('API_BASE_URL','https://sandbox.wallets.africa/').'self/transactions',[
             'Currency'=> $currency,
             'skip'=> $skip,
             'take'=> $take,
             'dateFrom'=> $dateFrom,
             'dateTo'=> $dateTo,
             'transactionType'=> $tType,
             'SecretKey'=>env('Secret_Key', 'hfucj5jatq8h'),
         ]);
 
         return $response->json();
     }

     public function verifyBvn(Request $request){
        $validator = Validator::make($request->all(),[
            'bvn'=>'required',
            'dateOfBirth'=>'required',

        ]);
        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $bvn = $request['bvn'];
        $dob = $request['dateOfBirth'];
        if (Str::length($bvn) === 11){
            $response = Http::withHeaders(
                [
                    'Authorization'=> 'Bearer ' . env('Public_Key'),
                ]
            )->post(env('API_BASE_URL','https://sandbox.wallets.africa/').'self/verifybvn',[
                'bvn'=> $bvn,
                'dateOfBirth'=> $dob,
                'SecretKey'=>env('Secret_Key', 'hfucj5jatq8h'),
            ]);
    
        }else{
            return response()->json(['error'=>'BVN must be 11 digits'], 400);
        }
         return $response->json();
     }
}
