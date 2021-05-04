<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function index()
    {
        return Role::all();
    }


    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|unique:roles"
        ]);
        $role = Role::create($request->all());
        return response()->json([
          $role
        ],201);
    }

     public function show(Role $role)
    {
        return $role;
    }


    public function update(Request $request, Role $role)
    {
        $role->update($request->all());
        return response()->json([
            'message' => 'Registro actualizado correctamente'
        ],200);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            'message' => 'Registro eliminado correctamente'
        ]);
    }
}
