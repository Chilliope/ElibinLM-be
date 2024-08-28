<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RackController extends Controller
{
    public function index()
    {
        $rack = Rack::paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $rack
        ], 200);
    }

    public function show($id)
    {
        $rack = Rack::where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $rack
        ], 200); 
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'rack' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $rackExisting = Rack::where('rack', $request->rack)->exists();

        if($rackExisting) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Rak sudah terdaftar'
            ], 400);
        }

        $rack = Rack::create($request->all());

        return response()->json([
            'status' => 'success',
            'rack' => $rack->rack
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'rack' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $rackExisting = Rack::where('rack', $request->rack)
                            ->where('id', '!=', $id)
                            ->exists();

        if($rackExisting) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Rak sudah terdaftar'
            ], 400);
        }
        $rack = Rack::where('id', $id)->first();

        if(!$rack) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Rak tidak ditemukan'
            ], 404);
        }

        $rack->rack = $request->rack;
        $rack->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Rak berhasil diedit'
        ], 201);
    }

    public function destroy($id)
    {
        $rack = Rack::where('id', $id)->first();

        if(!$rack) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Rak tidak ditemukan'
            ], 404);
        }

        $books = Book::where('rack_id', $rack->id)->get();

        if($books->isNotEmpty()) {
            foreach ($books as $book) {
                $book->delete();
            }
        }
        
        $rack->delete();        

        return response()->json([], 204);
    }
}
