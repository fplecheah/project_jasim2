<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginUserRequest $request){
        $request->validated($request->all());
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'status'=>'error',
                'message'=>'credentials do not match',
                'data'=>''
            ],401);
           }

           $user = Auth::user();
           return response()->json([
            "status"=>'success',
            'message'=>'successfully logged in',
            'data' => $user,
            'token' =>$user->createToken('Api  Token of '.$user->name)->plainTextToken
           ],200);
    }


    //THIS IS FOR USER REGISTRATION
    public function register(StoreUserRequest $request){
        $request->validated($request->all());
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> $request->password,
        ]);
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }


    public function logout(){
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json(['message' => 'You have Logged out successfully']);
    }
}
