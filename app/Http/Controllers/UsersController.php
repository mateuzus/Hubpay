<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\WalletProfile;
use App\Http\Controllers\IdentityController;
use Illuminate\Http\Client\Response;
class UsersController extends Controller
{
    //
    public $successStatus = 200;
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $userProfile = new WalletProfile();
        $userProfile['user_id']=$user->id;
        $userProfile->save();
        // $profile = WalletProfile::create($userProfile);
        $success['token'] = $user->createToken('hubpay')->accessToken;
        $success['name'] =$user->name;
        return response()->json(['success'=> $success], $this->successStatus);


    }

    public function login(Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('hubpay')->accessToken;
            return response()->json(['success'=>$success], $this->successStatus);
        }else{
            return response()->json(['error'=>'Unauthorized'], 401);
        }
    }

    public function details(){
        $user = Auth::user();
        return response()->json(['success'=>$user], $this->successStatus);
    }

    public function profileUpdate(Request $request){
        $validate = Validator::make($request->all(),[
            'first_name'=>'required',
            'last_name'=>'required',
            'phone_no'=>'required',
            'Address'=>'required',
            'bvn'=> 'required',
        ]);

        $user = Auth::user();
        $identity = new IdentityController();
        $response = $identity->getBvn($request);
        if ($response->status()=== 200){
            $bnvDetails = json_decode($response->content(), true);
            //check if all bvn values are correct
            if(
                $user->email !== $bnvDetails['Email'] || $request->first_name !== $bnvDetails['FirstName'] ||
                $request->last_name !== $bnvDetails['LastName'] || $request->phone_no !== $bnvDetails['PhoneNumber'] 
            ){
                return response()->json(['error'=> 'Wrong Bvn details'], 400);
            }else{
                //save profile
                $input = $request->all();
                $userProfile = WalletProfile::where('user_id',$user->id)->first();
                $userProfile->update($input);
                $profile = WalletProfile::find($userProfile->id);
                return response()->json([
                    'success'=> true,
                    'data'=> (string) $profile,
                    'message'=>'User Profile Updated Successfully',
                ], 200);
            }
        }else{
            return response()->json(['error'=> 'cannot resolve bvn'], 400);
        }
        

    }
    
}
