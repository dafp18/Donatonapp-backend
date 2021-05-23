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
                'Message' => 'emailFail'
            ],401);
        }

        if($user && !Hash::check($request->password, $user->password)){
            return response()->json([
                'Message' => 'passwordFail'
            ],401);
        }

        if($user && Hash::check($request->password, $user->password) && $user->is_active == false){
            return response()->json([
                'Message' => 'disabled'
            ],401);
        }

        return response()->json([
           'userId' => $user->id,
           'rol' => $user->id_rol,
           'ultimoIngreso' => $user->updated_at,
           'name' => $user->name,
           'lastname' => $user->lastname,
           'user' => $user->email,
           'token' => $user->createToken($request->device_name)->plainTextToken
        ],200);
    }
}
