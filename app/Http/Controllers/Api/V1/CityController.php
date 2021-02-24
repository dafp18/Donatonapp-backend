<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index()
    {
        return City::all();
    }

    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required|unique:cities',
           'id_department' => 'required|integer'
        ]);
        $city = City::create($request->all());
        return response()->json([
            $city
        ],201);
    }

    public function show(City $city)
    {
        return $city;
    }

    public function update(Request $request, City $city)
    {
        $city->update($request->all());
        return response()->json([
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function destroy(City $city)
    {
        $city->delete();
        return response()->json([
            'message' => 'Registro eliminado correctamente'
        ]);
    }
}
