<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Validator;
use Hash;

class AuthController extends Controller
{
    public function getCurrentUser(Request $request){
        
        $data['user'] =new UserResource($request->user());
        
        return $this->successWithData($data, '');
    }
    
    public function register(Request $request){
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'nullable|required|email',
            'password' => 'required|between:6,20|confirmed',
            'password_confirmation' => 'required|same:password',
            'mobile_no' => 'required|regex:/^[0-9]+$/|between:9,12',
            'country_code' => 'required|numeric'
        ]);
   
        if($validator->fails()){
            return $this->validatorFails($validator->errors());       
        }
        
        $user=new User();
        $user->fill($request->all());
        $user->password= Hash::make($request->password);
        $user->country_code = $request->country_code;
        $user->mobile_no = $request->mobile_no;
        $user->save();

        $wallet=new Wallet();
        $wallet->user_id = $user->id;
        $wallet->amount = 0;
        $wallet->save();
        
        $token = $user->createToken('login');
        
        $data['user'] =new UserResource($user);
        $data['api_token'] = $token->plainTextToken;
        
        return $this->successWithData($data, 'register success');
    }
    
    public function login(Request $request){
        
         $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $this->validatorFails($validator->errors());       
        }
        
        $loginData = ['email' => $request->email, 'password' => $request->password];

        if (!auth()->attempt($loginData)) 
        {
            return $this->errorWithData([], 'Invalid Credentials.');
        }

        $user = auth()->user();
        
        $data['user'] =new UserResource($user);
        $user->tokens()->delete();

        $wallet = Wallet::where('user_id',$user->id)->first();

        if(!$wallet)
        {
            $wallet=new Wallet();
            $wallet->user_id = $user->id;
            $wallet->amount = 0;
            $wallet->save();
        }

        $token = $user->createToken('login');

        $data['api_token'] = $token->plainTextToken;
        $data['wallet'] = $wallet;
        
        return $this->successWithData($data, 'login success');
    }
    
    public function resetPassword(Request $request){
        
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|between:6,20|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->validatorFails($validator->errors());       
        }
        $user=User::where('email',$request->email)->first();

        if(!$user)
        {
            return $this->errorWithData([], 'User not found with email input');
        }
        
        $user->password= Hash::make($request->password);
        $user->save();
    
        return $this->successWithData([], 'Password has been updated');
    }

}
