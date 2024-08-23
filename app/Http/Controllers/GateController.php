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
        $gate = Gate::with(['books'])->get();

        return response()->json([
            'data' => $gate
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'borrower_name' => 'required',
            'book_id' => 'required',
            'total' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $checkGate = Gate::where('book_id', $request->book_id)->exists();

        if($checkGate) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Buku sudah ada dikeranjang'
            ], 400);
        }

        $gate = Gate::create($request->all());

        return response()->json([
            'status' => 'success',
            'gate_id' => $gate->id
        ], 201);
    }
}
