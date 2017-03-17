<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class ApiAuthController extends Controller
{

    public function show(){
        $users = User::all();
        $response = [
            'users' => $users
        ];
        return response()->json($response, 201);
    }

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

    public function register(Request $request){
        //Retrieving data from the front end

        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');

//        saving data by using model
        $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => bcrypt($password)
            ]);

//        Now creating token for that user
        $token = JWTAuth::fromUser($user);
        return response()->json(['token' => $token, 'user' => $user], 200);
     }
}
