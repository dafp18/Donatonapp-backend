<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Type_document;
use Illuminate\Http\Request;

class TypeDocumentController extends Controller
{

    public function index()
    {
        return Type_document::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|unique:type_documents",
            "short_name" => "required"
        ]);
        $type_doc = Type_document::create($request->all());
        return response()->json([
            $type_doc
        ],201);
    }

    public function show(Type_document $type_document)
    {
        return $type_document;
    }

    public function update(Request $request, Type_document $type_document)
    {
        $type_document->update($request->all());
        return response()->json([
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function destroy(Type_document $type_document)
    {
        $type_document->delete();
        return response()->json([
            'message' => 'Registro eliminado correctamente'
        ]);
    }
}
