<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //login api
    public function login(Request $request) {
        //validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //check if the user exists
        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json([
                'message' => 'User does not exist'
            ], 401);
        }

        //check password
        if(!\Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }



        //generate token
        $token = $user->createToken('token')->plainTextToken;

        //response
        return response()->json([
            'message' => 'success',
            'user' => $user,
            'token' => $token
        ], 200);
    }


}
