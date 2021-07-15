<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    public $base_url = "https://donatonappco.herokuapp.com/public";
    public function index(){
        $query = "select   p.id,
                           p.name title,
                           CONCAT('$this->base_url/imgsDonations/',split_part(p.url_image, '|', 1)) url_image,
                           p.description,
                           p.quantity,
                           p.observation,
                           p.created_at,
                           c.name category,
                           sd.name state_donation,
                           sp.name state_product,
                           l.name locality
                    from products p
                    inner join users u on p.id_user = u.id
                    inner join state_products sp on p.id_state_product = sp.id
                    inner join state_donations sd on p.id_state_donation = sd.id
                    inner join categories c on p.id_category = c.id
                    inner join localities l on p.id_locality = l.id
                    where sd.name = 'Activa'
                    order by p.created_at desc
                 ";
        $dataDonations = DB::select($query);
        return $dataDonations;
    }

    public function getProductsByUser(Request $request){
        $query = "select  p.id,
                           p.name title,
                           CONCAT('$this->base_url/imgsDonations/',split_part(p.url_image, '|', 1)) url_image,
                           p.description,
                           p.quantity,
                           p.observation,
                           p.created_at,
                           p.updated_at,
                           c.name category,
                           sd.name state_donation,
                           sp.name state_product,
                           l.name locality
                    from products p
                    inner join users u on p.id_user = u.id
                    inner join state_products sp on p.id_state_product = sp.id
                    inner join state_donations sd on p.id_state_donation = sd.id
                    inner join categories c on p.id_category = c.id
                    inner join localities l on p.id_locality = l.id
                    where p.id_user = '$request->userId'
                    order by p.created_at desc";
        return DB::select($query);
    }

    public function getProductsAceptedAndProcessed(Request $request){
        $query = "select   p.id,
                           p.name title,
                           CONCAT('$this->base_url/imgsDonations/',split_part(p.url_image, '|', 1)) url_image,
                           p.description,
                           p.quantity,
                           p.observation,
                           p.created_at,
                           p.updated_at,
                           p.id_user,
                           c.name category,
                           sd.name state_donation,
                           sp.name state_product,
                           l.name locality
                    from products p
                    inner join users u on p.id_user_take_donate = u.id
                    inner join state_products sp on p.id_state_product = sp.id
                    inner join state_donations sd on p.id_state_donation = sd.id
                    inner join categories c on p.id_category = c.id
                    inner join localities l on p.id_locality = l.id
                    where u.email = '$request->userTakeDonate'
                    and p.id_state_donation = $request->estado
                    order by p.updated_at desc";
        return DB::select($query);
    }

    public function separateProduct(Request $request){
        $dataProduct =     DB::table('products')
                        ->join('users', 'users.id', '=', 'products.id_user')
                        ->select('users.email','users.name as userDonation', 'products.name')
                        ->where('products.id', $request->idProduct)
                        ->get();
        $namefundation = DB::table('users')
                            ->select('name')
                            ->where('id', $request->idUser)
                            ->get();
        $dataProduct = json_decode(json_encode($dataProduct),true);
        $namefundation = json_decode(json_encode($namefundation),true);
        $dataEmail = [
                        'nameDonante' => $dataProduct[0]['userDonation'],
                        'fundacionName' => $namefundation[0]['name']
                     ];
        $sendTo = $dataProduct[0]['email'];

        $result =   DB::table('products')
                        ->where('id', $request->idProduct)
                        ->update([  'id_user_take_donate' => $request->idUser,
                                    'id_state_donation' => 2
                                ]);
        if($result){
            $subject = "¡Donaste! Coordina la entrega de tu donación ".$dataProduct[0]['name'];
            Mail::send('emails.email_takeDonation', $dataEmail, function($message) use ($sendTo,$subject,$dataEmail) {
                $message->to($sendTo)->subject($subject);
            });
            return response()->json([
               'Message' => 'Actualizado'
            ]);
        }

        return response()->json([
            'Message' => 'Error'
        ]);
    }

    public function changeStatusFinishProduct ($idProduct) {
        $result = DB::table('products')
                        ->where('id',$idProduct)
                        ->update(['id_state_donation' => 3]);
        if($result){
            return response()->json([
                'Message' => 'Actualizado'
            ]);
        }
    }

    public function changeStatusInactiveProduct ($idProduct) {
        $result = DB::table('products')
            ->where('id',$idProduct)
            ->update(['id_state_donation' => 5]);
        if($result){
            return response()->json([
                'Message' => 'Actualizado'
            ]);
        }
    }

    public function changeStatusActiveProduct ($idProduct) {
        $result = DB::table('products')
            ->where('id',$idProduct)
            ->update(['id_state_donation' => 1]);
        if($result){
            return response()->json([
                'Message' => 'Actualizado'
            ]);
        }
    }

    public function store(Request $request){
        $fileName = strtotime("now");
        $dirImages = public_path("/imgsDonations");
        $totalImages = [];
        $stringImages = '';
        for($i = 1; $i <= $request->cantImages; $i++ ){
            //  Asi Funciona desde el postman
            if($request->hasFile('url_image_'.$i)){
                $file = $request->file('url_image_'.$i);
                $name = 'donationImage_'.$fileName.'_'.$i.'.'.$file->getClientOriginalExtension();
                $file->move($dirImages,$name);
                array_push($totalImages,$name);
                $stringImages .= $name.'|';
            }
        }
        $request->merge(['url_image' => $stringImages]);
        $request->validate([
           'name' => 'required|string',
           'url_image' => 'required',
           'description' => 'required|string',
           'quantity' => 'required|integer',
           'observation' => 'required',
           'id_category' => 'required|integer',
           'id_state_donation' => 'required|integer', //activa-desactivada-entregada-enproceso
           'id_state_product' => 'required|integer',
           'id_locality' => 'required|integer',
           'id_user' => 'required|integer',
        ]);
        $product = Product::create([
            'name' => $request->name,
            'url_image' => $request->url_image,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'observation' => $request->observation,
            'id_category' => $request->id_category,
            'id_state_donation' => $request->id_state_donation,
            'id_state_product' => $request->id_state_product,
            'id_locality' => $request->id_locality,
            'id_user' => $request->id_user,
        ]);
        if($product){
            return response()->json([
               'Message' => 'creado'
            ],201);
        }
        return response()->json([
            'Message' => 'No creado'
        ]);

    }

    public function show(Product $product)
    {
        $idCategory = $product['id_category'];
        $idLocality = $product['id_locality'];
        $idStatus = $product['id_state_product'];
        $categoryName = json_decode(DB::table('categories')
                            ->select('name')
                            ->where("id", $idCategory)
                            ->get(), true
                        );
        $localityName = json_decode(DB::table('localities')
            ->select('name')
            ->where("id", $idLocality)
            ->get(), true
        );

        $StatusName = json_decode(DB::table('state_products')
            ->select('name')
            ->where("id", $idStatus)
            ->get(), true
        );
        $product['id_category'] = $categoryName[0]['name'];
        $product['id_locality'] = $localityName[0]['name'];
        $product['id_state_product'] = $StatusName[0]['name'];
        return $product;
    }

    public function updateProduct (Request $request){
        $fileName = strtotime("now");
        $dirImages = public_path("/imgsDonations");
        $totalImages = [];
        $stringImages = '';
        if(isset($request->cantImages)){
            for($i = 1; $i <= $request->cantImages; $i++ ){
                //  Asi Funciona desde el postman
                if($request->hasFile('url_image_'.$i)){
                    $file = $request->file('url_image_'.$i);
                    $name = 'donationImage_'.$fileName.'_'.$i.'.'.$file->getClientOriginalExtension();
                    $file->move($dirImages,$name);
                    array_push($totalImages,$name);
                    $stringImages .= $name.'|';
                }
            }
            if(isset($request->id_category)){
                $query = " update products set  name = '$request->name',
                                                quantity = '$request->quantity',
                                                id_category = '$request->id_category',
                                                id_state_product = '$request->id_state_product',
                                                observation = '$request->observation',
                                                url_image = '$stringImages'
                            where id = '$request->id'
                         ";
            }else{
               $query = " update products set  name = '$request->name',
                                                quantity = '$request->quantity',
                                                id_state_product = '$request->id_state_product',
                                                observation = '$request->observation',
                                                url_image = '$stringImages'
                           where id = '$request->id'
                 ";
            }

        }else{
            if(isset($request->id_category)){
                $query = " update products set  name = '$request->name',
                                                quantity = '$request->quantity',
                                                id_category = '$request->id_category',
                                                id_state_product = '$request->id_state_product',
                                                observation = '$request->observation'
                            where id = '$request->id'
                    ";
            }else{
                $query = " update products set  name = '$request->name',
                                                quantity = '$request->quantity',
                                                id_state_product = '$request->id_state_product',
                                                observation = '$request->observation'
                            where id = '$request->id'
                    ";
            }

        }

        $result = DB::update($query);

        if($result){
            return response()->json([
                'Message' => 'Actualizado'
            ],201);
        }
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return response()->json([
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Registro eliminado correctamente'
        ]);
    }

}
