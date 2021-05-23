<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\EmailConfirmation;
use App\Models\User;
use Carbon\Carbon;
use http\Env\Response;
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
            "lastname" => ($request->lastname == '.') ? '': $request->lastname,
            "user" => $request->user,
            "address" => $request->address,
            "phone" => $request->phone,
            "num_document" => $request->num_document,
            "email" => $request->email,
            "password" =>  Hash::make($request->password),
            "id_rol" => $request->id_rol,
            "id_document" => $request->id_document,
            "is_active" => $request->is_active,
            "image_url" => "http://192.168.1.18:8000/imgsUsers/profileDefault.png"
        ]);

        $dataEmail = [
            'email' => $request->email,
            'name' => $request->name.' '.$request->lastname
        ];

        $sendTo = $request->email;

        Mail::send('emails.email_confirmation', $dataEmail, function($message) use ($sendTo, $dataEmail) {
                $message->to($sendTo)->subject('Confirmación de correo');
        });
        return response()->json([
                'Message' => 'Registrado'
        ],201);

    }

    public function verifyEmail ($email){
        DB::table('users')
            ->where('email', $email)
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => Carbon::now(),
                      'is_active' => true]);

        return 'Email confirmado';
    }

    public function validateIfExistEmail (Request $request){
        $result = DB::table('users')
                    ->where('email', '=', $request->email)
                    ->get();

        $result = json_decode(json_encode($result),true);
        if(!empty($result)){
            $code = random_int(1111,9999);
            $dataEmail = [ 'code' => $code,
                           'name' => $result[0]['name']
                         ];
            $sendTo = $request->email;
            Mail::send('emails.email_forgotPassword', $dataEmail, function($message) use ($sendTo, $dataEmail) {
                $message->to($sendTo)->subject('Código de verificación DonatónApp');
            });
            DB::table('users')
                ->where('email', '=', $request->email)
                ->update(['remember_token' => $code]);

            return response()->json([
                'Message'=> 'Registrado'
            ]);
        }

        return response()->json([
            'Message' => 'Not found'
        ]);
    }

    public function verifyCodeForgetPassword (Request $request) {
        $result = DB::table('users')
                    ->where('email', '=', $request->email)
                    ->get();

        $result = json_decode(json_encode($result),true);

        if(!empty($result)){
            $code = $result[0]['remember_token'];
            if($code == $request->code){
                DB::table('users')
                    ->where('email', '=', $request->email)
                    ->update(['remember_token' => null]);
                return response()->json([
                    'Message' => 'Verificado'
                ]);
            }
        }

        return response()->json([
            'Message' => 'Incorrecto'
        ]);
    }

    public function changePassword (Request $request) {
        DB::table('users')
            ->where('email', '=', $request->email)
            ->update(['password' => Hash::make($request->newPassword)]);
        return response()->json([
            'Message' => 'Cambiada'
        ]);
    }
}
