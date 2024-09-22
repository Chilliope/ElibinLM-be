<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $visitor = Visitor::where('name', 'like', '%' . $request->search . '%')
        ->with(['major'])
        ->paginate(10);

        $visitorCount = Visitor::count();

        return response()->json([
            'status' => 'success',
            'count' => $visitorCount,
            'data' => $visitor
        ], 200);
    }

    public function show($id)
    {
        $visitor = Visitor::where('id', $id)->with(['major'])->first();

        return response()->json([
            'status' => 'success',
            'data' => $visitor
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'major_id' => 'required',
            'role' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $data = [
            'name' => $request->name,
            'major_id' => $request->major_id,
            'role' => $request->role
        ];

        $visitor = Visitor::create($data);

        return response()->json([
            'status' => 'success',
            'visitor' => $visitor->name
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'major_id' => 'required',
            'role' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $visitor = Visitor::where('id', $id)->first();

        if(!$visitor) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Pengunjung Perpustakaan tidak ditemukan'
            ], 404);    
        }

        $visitor->name = $request->name;
        $visitor->major_id = $request->major_id;
        $visitor->role = $request->role;
        $visitor->save();

        return response()->json([
            'status' => 'success',
            'visitor' => $visitor->name 
        ], 201);
    }

    public function destroy($id)
    {
        $visitor = Visitor::where('id', $id)->first();

        if(!$visitor) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Pengunjung Perpustakaan tidak ditemukan'
            ], 404);    
        }

        $visitor->delete();

        return response()->json([], 204);
    }
}
