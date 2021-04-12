<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function registerNewUser(Request $request) {
        $request->validate([
            "name" => "required|unique:type_documents",
            "lastname" => "required|string",
            "user" => 'required|email|unique:users',
            "address" => 'required|string',
            "phone" => 'required|integer',
            "num_document" => 'required|integer',
            "email" => 'required|email|unique:users',
            "password" => 'required',
            "id_rol" => 'required|integer',
            "id_document" => 'required|integer',
            "is_active" => 'required'
        ]);
        $user = User::create([
            "name" => $request->name,
            "lastname" => $request->lastname,
            "user" => $request->user,
            "address" => $request->address,
            "phone" => $request->phone,
            "num_document" => $request->num_document,
            "email" => $request->email,
            "password" =>  Hash::make($request->password),
            "id_rol" => $request->id_rol,
            "id_document" => $request->id_document,
            "is_active" => $request->is_active
        ]);
        return response()->json([
            $user
        ],201);
    }
}
