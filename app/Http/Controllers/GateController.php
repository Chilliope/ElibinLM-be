<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\Gates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GateController extends Controller
{
    public function index()
    {
        $gate = Gate::with(['book'])->get();
        $gateCount = Gate::count();

        return response()->json([
            'count' => $gateCount,
            'data' => $gate
        ], 200);
    }

    public function store(Request $request, $id)
    {
        $checkGate = Gate::where('book_id', $id)->exists();

        if($checkGate) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Buku sudah ada dikeranjang'
            ], 400);
        }   

        $data = [
            'book_id' => $id
        ];

        $gate = Gate::create($data);

        return response()->json([
            'status' => 'success',
            'gate_id' => $gate->id
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $gate = Gate::where('id', $id)->update($request->all());
    }

    public function destroy($id)
    {
        $gate = Gate::where('id', $id)->delete();
    }
}
