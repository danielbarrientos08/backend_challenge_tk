<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        Validator::make($request->all(), [
            'email' => 'bail|required|email',
            'password' => 'required',
        ])->validate();

        if(!Auth::attempt($request->only('email','password')))
        {
            return response()->json([
                'status'=>'failed',
                'message'=>'Email o contraseña incorrecto.'
            ],401);
        }

        $token =Auth::user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'=>'success',
            'api_token' => $token,
            'token_type' => 'Bearer'
        ],200);
    }
}
