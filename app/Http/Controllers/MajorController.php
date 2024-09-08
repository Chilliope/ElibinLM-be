<?php

namespace App\Http\Controllers;

use App\Models\ClassTable;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index(Request $request)
    {
        $major = Major::where('major', 'like', '%' . $request->search . '%')
        ->paginate(10);

        $majorCount = Major::count();

        return response()->json([
            'status' => 'success',
            'count' => $majorCount,
            'message' => $major
        ]);
    }

    public function getAllMajor()
    {
        $major = Major::get();

        return response()->json([
            'status' => 'success',
            'message' => $major
        ]);
    }

    public function show($id)
    {
        $major = Major::where('id', $id)->first();

        if(!$major) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $major
        ]);
    }

    public function store(Request $request)
    {
        $major = Major::create($request->all());

        return response()->json([
            'status' => 'success',
            'major' => $major->major
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $major = Major::where('id', $id)->first();

        if(!$major) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }

        $major->major = $request->major;
        $major->save();

        return response()->json([
            'status' => 'success',
            'major' => $major->major
        ], 201);
    }

    public function destroy($id)
    {
        $major = Major::where('id', $id)->first();

        if(!$major) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }

        $class = ClassTable::where('major_id', $major->id)->get();

        if($class->isNotEmpty()) {
            foreach($class as $class) {
                $class->delete();
            }
        }

        $major->delete();

        return response()->json([], 204);
    }
}
