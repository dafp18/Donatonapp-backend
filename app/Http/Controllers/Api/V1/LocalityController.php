<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locality;

class LocalityController extends Controller
{
    public function index()
    {
        return Locality::all();
    }

    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required|unique:localities',
           'id_city' => 'required|integer'
        ]);
        $locality = Locality::create($request->all());
        return response()->json([
           $locality
        ]);
    }

    public function show(Locality $locality)
    {
        return $locality;
    }

    public function update(Request $request, Locality $locality)
    {
        $locality->update($request->all());
        return response()->json([
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function destroy(Locality $locality)
    {
        $locality->delete();
        return response()->json([
            'message' => 'Registro eliminado correctamente'
        ]);
    }
}
