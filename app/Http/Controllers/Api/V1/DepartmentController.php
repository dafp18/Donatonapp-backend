<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::all();
    }

    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required|unique:departments'
        ]);
        $department = Department::create($request->all());
        return response()->json([
            $department
        ],201);
    }

    public function show(Department $department)
    {
        return $department;
    }

    public function update(Request $request, Department $department)
    {
        $department->update($request->all());
        return response()->json([
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json([
            'message' => 'Registro eliminado correctamente'
        ]);
    }
}
