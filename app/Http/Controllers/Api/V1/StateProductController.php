<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State_product;

class StateProductController extends Controller
{
    public function index()
    {
        return State_product::all();
    }

    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required|unique:state_products'
        ]);
        $stateProduct = State_product::create($request->all());
        return response()->json([
            $stateProduct
        ],201);
    }

    public function show(State_product $state_product)
    {
        return $state_product;
    }

    public function update(Request $request, State_product $state_product)
    {
        $state_product->update($request->all());
        return response()->json([
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function destroy(State_product $state_product)
    {
        $state_product->delete();
        return response()->json([
            'message' => 'Registro eliminado correctamente'
        ]);
    }
}
