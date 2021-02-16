<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Client\Response;
use Validator;
use App\User;
use App\WalletTransaction;

class AirtimeController extends Controller
{
    //
    // protected $key = env('Public_Key');

    public function getProviders(){
        $response = Http::withHeaders([
            'Authorization'=> 'Bearer ' . env('Public_Key'),
        ])->post(env('API_BASE_URL','https://sandbox.wallets.africa/').'bills/airtime/providers',[
            // "Code"=> "1000",
            // "Amount"=> 100,
            // "PhoneNumber"=> "07068260000",
            // 'SecretKey'=>'hfucj5jatq8h',
        ]);

        return $response->json();

    }

    public function getAirtime(Request $request){
        $validator = Validator::make($request->all(),[
            'Code'=>'required',
            'Amount'=> 'required',
            'PhoneNumber'=>'required',
        ]);
        // check balance of logged in user
        $loggedInUser = Auth::user();
        $user = User::findOrFail($loggedInUser->id);
        $balance = $user->walletProfile->balance;
        if ($balance < $request['Amount']){
            return response()->json(['error'=>'Insufficient balance !!!'], 500);
        }

        $provider = $request['Code'];
        if (Str::lower($provider) === 'airtel' || Str::lower($provider) === 'mtn' || Str::lower($provider) === 'etisalat' || Str::lower($provider) === 'glo'){
            $response = Http::withHeaders([
                'Authorization'=> 'Bearer ' . env('Public_Key'),
                'Content-Length'=> 100,
            ])->post(env('API_BASE_URL','https://sandbox.wallets.africa/').'bills/airtime/purchase',[
                "Code"=> $provider,
                "Amount"=> $request['Amount'],
                "PhoneNumber"=> $request['PhoneNumber'],
                'SecretKey'=>env('Secret_Key', 'hfucj5jatq8h'),
            ]);
            if ($response->status() === 200){
                //get reference code
                $json_array = $response->json();
                $refNo = $json_array['TransactionReference'];
                //save to wallet
                $input = [
                    'user_id'=>$loggedInUser->id,
                    'refno'=>$refNo,
                    'narration'=>'Purchase of airtime',
                    'record_type_id'=>2,
                    'amount'=>$request['Amount'],

                ];
                WalletTransaction::create($input );
            }
        }else{
            return response()->json(['error'=>'Invalid provider !! purchase cannot continue'], 500);
        }

        return $response->json();

    }

}

