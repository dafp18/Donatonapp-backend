<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        return DB::table('products')
            ->join('users', 'users.id', '=', 'products.id_user')
            ->join('state_products', 'state_products.id', '=', 'products.id_state_product')
            ->join('state_donations', 'state_donations.id', '=', 'products.id_state_donation')
            ->join('categories', 'categories.id', '=', 'products.id_category')
            ->join('localities', 'localities.id', '=', 'products.id_locality')
            ->select('products.id','products.name as title','products.url_image','products.description','products.quantity','products.observation', 'products.created_at',
                'categories.name as category',
                'state_donations.name as state_donation',
                'state_products.name as state_product',
                'localities.name as locality'
            )
            ->where('state_donations.name', '=', 'Pendiente')
            ->orderBy('products.created_at', 'desc')
            ->get();
    }

    public function getProductsByUser(Request $request){
        return DB::table('products')
            ->join('users', 'users.id', '=', 'products.id_user')
            ->join('state_products', 'state_products.id', '=', 'products.id_state_product')
            ->join('state_donations', 'state_donations.id', '=', 'products.id_state_donation')
            ->join('categories', 'categories.id', '=', 'products.id_category')
            ->join('localities', 'localities.id', '=', 'products.id_locality')
            ->select('products.id','products.name as title','products.url_image','products.description','products.quantity','products.observation', 'products.created_at',
                'categories.name as category',
                'state_donations.name as state_donation',
                'state_products.name as state_product',
                'localities.name as locality'
            )
            ->where([
                ['users.email', '=', $request->user],
                ['state_donations.name', '=', $request->estado],
            ])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required|string',
           'url_image' => 'required|string',
           'description' => 'required|string',
           'quantity' => 'required|integer',
           'observation' => 'required',
           'id_category' => 'required|integer',
           'id_state_donation' => 'required|integer',
           'id_state_product' => 'required|integer',
           'id_locality' => 'required|integer',
           'id_user' => 'required|integer',
        ]);
        $product = Product::create($request->all());
        return response()->json([
            $product
        ],201);
    }

    public function show(Product $product)
    {
        return $product;
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
