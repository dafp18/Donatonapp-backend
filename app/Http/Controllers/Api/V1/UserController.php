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
        $query = "select  u.id,
                        u.name,
                        lastname,
                        email,
                        CONCAT('http://192.168.1.18:8000/imgsUsers/',image_url) image_url,
                        address,
                        phone,
                        num_document,
                        r.name as rol,
                        u.created_at
                from users u
                inner join roles r on u.id_rol = r.id
                where u.email = '$request->email'";
        return DB::select($query);
    }

    public function getDataUserDonation ($id){
        $query = "select name,
                         lastname,
                         email,
                         CONCAT('http://192.168.1.18:8000/imgsUsers/',image_url) image_url,
                         phone
                    from users
                    where id = '$id'";
        return DB::select($query);
    }

    public function updateDataUser (Request $request){
        $fileName = strtotime("now");
        $dirImages = public_path("/imgsUsers");
        $name = '';
        if(isset($request->url_image)){
            if($request->hasFile('url_image')){
                $file = $request->file('url_image');
                $name = 'profileImage_'.$fileName.'.'.$file->getClientOriginalExtension();
                $file->move($dirImages,$name);
                $query = " update users set image_url = '$name' where id = '$request->id'";
            }
        }else{
            $query = " update users set address = '$request->address', phone = '$request->phone' where id = '$request->id'";
        }

        $result = DB::update($query);

        if($result){
            return response()->json([
                'Message' => 'Actualizado'
            ],201);
        }
        return response()->json([
            'Message' => 'Error'
        ]);
    }
}
