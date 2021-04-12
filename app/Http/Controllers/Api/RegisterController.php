<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\EmailConfirmation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function registerNewUser(Request $request) {
        $request->validate([
            "name" => "required|string",
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

        $dataEmail = [
            'email' => $request->email,
            'name' => $request->name.' '.$request->lastname
        ];

        $sendTo = $request->email;
        /*Mail::to($sendTo)->send(new EmailConfirmation());*/
        Mail::send('emails.email_confirmation', $dataEmail, function($message) use ($sendTo, $dataEmail) {
                $message->to($sendTo)->subject('Confirmación de correo');
        });
        return response()->json([
                $user
        ],201);

    }

    public function verifyEmail ($email){
        DB::table('users')
            ->where('email', $email)
            ->update(['email_verified_at' => Carbon::now()]);

        return 'Email confirmado';
    }
}
