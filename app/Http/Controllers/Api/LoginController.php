<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'message' => 'Email no registrado'
            ],401);
        }

        if($user && !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Contraseña inválida'
            ],401);
        }

        if($user && Hash::check($request->password, $user->password) && $user->is_active == false){
            return response()->json([
                'message' => 'Su email no ha sido verificado'
            ],401);
        }

        return response()->json([
           'user' => $user->email,
           'token' => $user->createToken($request->device_name)->plainTextToken
        ],200);
    }
}
