<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
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
