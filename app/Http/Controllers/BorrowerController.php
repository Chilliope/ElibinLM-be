<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BorrowerController extends Controller
{
    public function index()
    {
        $borrower = Borrower::with(['class', 'book'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $borrower
        ], 200);
    }

    public function show($id)
    {
        $borrower = Borrower::where('id', $id)->with(['class', 'book'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $borrower
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'class_id' => 'required|exists:class,id',
            'book_id' => 'required|exists:books,id',
            'amount' => 'required',
            'date_of_borrowing' => 'required',
            'date_of_return' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $borrower = Borrower::create($request->all());

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'class_id' => 'required|exists:class,id',
            'book_id' => 'required|exists:books,id',
            'amount' => 'required',
            'date_of_borrowing' => 'required',
            'date_of_return' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $borrower = Borrower::where('id', $id)->first();

        if(!$borrower) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Peminjam tidak ditemukan'
            ], 404);
        }

        $borrower->update($request->all());

        return response()->json([
            'status' => 'success'
        ], 201);
    }

    public function destroy($id) 
    {
        $borrower = Borrower::where('id', $id)->first();

        if(!$borrower) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Peminjam tidak ditemukan'
            ], 404);
        }

        $borrower->delete();

        return response()->json([], 204);
    }
}
