<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class ApiAuthController extends Controller
{
    public function authenticate(){
        //get user data
        $credentials = request()->only('email', 'password');
        try {
            $token = JWTAuth::attempt($credentials);
            if (!$token){
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

        }catch(JWTException $e){
            return response()->json(['error' => 'something_went_wrong'], 500);
        };
        //check the user creentials

        //generate jwt tokens
        return response()->json(['token' => $token], 200);
    }

    public function register(){
        //Retrieving data from the front end
        $name = request()->name;
        $email = request()->email;
        $password = request()->password;

        //saving data by using model
        $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password)
            ]);

        //Now creating token for that user
        $token = JWTAuth::fromUser($user);
        return response()->json(['token' => $token], 200);
     }
}
