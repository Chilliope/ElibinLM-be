<?php

namespace App\Http\Controllers;

use App\Models\SubBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubBookController extends Controller
{
    public function index($bookId)
    {
        $subBook = SubBook::where('book_id', $bookId)->with(['book'])->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $subBook
        ], 200);
    }

    public function show($id)
    {
        $subBook = SubBook::where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $subBook
        ], 200);
    }

    public function store(Request $request, $bookId)
    {
        $validated = Validator::make($request->all(), [
            'ISBN' => 'required|unique:sub_books,ISBN',
        ]);

        if($validated->fails()) {
            return response()->json($validated->errors(), 400);
        }

        $lastSubBook = SubBook::where('book_id', $bookId)->latest()->first();
        $copy = 0;

        if(!$lastSubBook) {
            $copy = 1;
        } else {
            $copy = $lastSubBook->copy + 1;
        }

        $data = [
            'book_id' => $bookId,
            'ISBN' => $request->ISBN,
            'copy' => $copy
        ];

        $subBook = SubBook::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Sub Book berhasil dibuat'
        ], 201);
    }

    public function update(Request $request, $id) 
    {
        $validated = Validator::make($request->all(), [
            'ISBN' => 'required|unique:sub_books,ISBN,'. $id,
        ]);

        if($validated->fails()) {
            return response()->json($validated->errors(), 400);
        }

        $subBook = SubBook::where('id', $id)->first();

        if(!$subBook) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $subBook->ISBN = $request->ISBN;
        $subBook->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Sub Book berhasil diupdate'
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $subBook = SubBook::where('id', $id)->first();

        if(!$subBook) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $subBook->delete();

        return response()->json([], 204);
    }
}
