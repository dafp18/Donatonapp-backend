<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getDataUserLogged (Request $request){
        $user = DB::table('users')
                    ->join('roles', 'roles.id', '=', 'users.id_rol')
                    ->select('users.id','users.name', 'lastname', 'email', 'image_url', 'address', 'phone', 'num_document', 'roles.name as rol', 'users.created_at' )
                    ->where('email', '=', $request->email)
                    ->get();
        return $user;
    }
}
