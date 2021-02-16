<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use illuminate\Http\Response;
use Validator;
class PayOutsController extends Controller
{
    //
    public function getBanks(){
        $response = Http::withHeaders([
            'Authorization'=> 'Bearer ' . env('Public_Key'),
        ])->post(env('API_BASE_URL','https://sandbox.wallets.africa/').'transfer/banks/all',[

        ]);

        return $response->json();

    }

    public function getBankAccountDetails(Request $request){
        $validator = Validator::make($request->all(),[
            'BankCode'=>'required',
            'AccountNumber'=> 'required',
        ]);

        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $bankCode = $request['BankCode'];
        $acctNo = $request['AccountNumber'];

        $response = Http::withHeaders([
            'Authorization'=> 'Bearer ' . env('Public_Key'),
            'Content-Length'=> 100,
        ])->post(env('API_BASE_URL','https://sandbox.wallets.africa/').'transfer/bank/account/enquire',[
            "BankCode"=> $bankCode,
            "AccountNumber"=> $acctNo,
            'SecretKey'=>env('Secret_Key', 'hfucj5jatq8h'),
        ]);
        if ($response->status() === 200){
            //Show Response
            return $response->json();
        }


    }

    public function transfer(Request $request){
        $validator = Validator::make($request->all(),[
            'BankCode'=>'required',
            'AccountNumber'=> 'required',
            'AccountName'=> 'required',
            'TransactionReference'=>'required',
            'Amount'=> 'required',
            'Narration'=> 'required'
        ]);

        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $bankCode = $request['BankCode'];
        $acctNo = $request['AccountNumber'];
        $name = $request['AccountName'];
        $refno = $request['TransactionReference'];
        $amt = $request['Amount'];
        $remarks = $request['Narration'];

        $response = Http::withHeaders([
            'Authorization'=> 'Bearer ' . env('Public_Key'),
            'Content-Length'=> 100,
        ])->post(env('API_BASE_URL','https://sandbox.wallets.africa/').'transfer/bank/account',[
            'BankCode'=> $bankCode,
            'AccountNumber'=> $acctNo,
            'AccountName'=> $name,
            'TransactionReference'=>$refno,
            'Amount'=> $amt,
            'Narration'=> $remarks,
            'SecretKey'=>env('Secret_Key', 'hfucj5jatq8h'),
        ]);
        if ($response->status() === 200 && $response['ResponseCode'] === 100){
            //Show Response
            return $response->json();
        }else{
            return $response->json();
        }


    }
}
