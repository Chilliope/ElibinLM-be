<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rack;
use Illuminate\Http\Request;

class RackController extends Controller
{
    public function index()
    {
        $rack = Rack::get();

        return response()->json([
            'status' => 'success',
            'data' => $rack
        ], 200);
    }

    public function show($id)
    {
        $rack = Rack::where('id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $rack
        ], 200); 
    }

    public function store(Request $request)
    {
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

        $book = Book::where('rack_id', $rack->id)->get();

        $rack->delete();

        if($book) {
            $book->delete();
        }

        return response()->json([], 204);
    }
}
