<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State_donation;

class StateDonationController extends Controller
{
    public function index()
    {
        return State_donation::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:state_donations',
        ]);
        $stateDonation = State_donation::create($request->all());
        return response()->json([
           $stateDonation
        ],201);
    }

    public function show(State_donation $state_donation)
    {
       return $state_donation;
    }

    public function update(Request $request, State_donation $state_donation)
    {
        $state_donation->update($request->all());
        return response()->json([
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function destroy(State_donation $state_donation)
    {
        $state_donation->delete();
        return response()->json([
            'message' => 'Registro eliminado correctamente'
        ]);
    }
}
