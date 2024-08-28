<?php

namespace App\Http\Controllers;

use App\Models\ClassTable;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    public function index()
    {
        $class = ClassTable::with(['major'])->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $class
        ], 201);
    }

    public function show($id)
    {
        $class = ClassTable::with(['major'])->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $class
        ], 201);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'class' => 'required',
            'major_id' => 'required',
            'alphabet' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $major = Major::where('id', $request->major_id)->first();

        if(!$major) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }

        $classExisting = ClassTable::where('class', $request->class)
                                   ->where('major_id', $request->major_id)
                                   ->where('alphabet', $request->alphabet)
                                   ->exists();
        
        if($classExisting) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Kelas ini sudah terdaftar'
            ], 400);
        }

        $data = [
            'class' => $request->class,
            'major_id' => $request->major_id,
            'alphabet' => $request->alphabet,
            'class_fix' => $request->class . ' ' . $major->major . ' ' . $request->alphabet
        ];

        $class = ClassTable::create($data);

        return response()->json([
            'status' => 'success',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'class' => 'required',
            'major_id' => 'required',
            'alphabet' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $major = Major::where('id', $request->major_id)->first();

        if(!$major) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }

        $classExisting = ClassTable::where('class', $request->class)
                                   ->where('major_id', $request->major_id)
                                   ->where('alphabet', $request->alphabet)
                                   ->where('id', '!=', $id)
                                   ->exists();
        
        if($classExisting) {
            return response()->json([   
                'status' => 'failed',
                'message' => 'Kelas ini sudah terdaftar'
            ], 400);
        }

        $class = ClassTable::where('id', $id)->first();

        if(!$class) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        }

        $class->class = $request->class;
        $class->major_id = $request->major_id;
        $class->alphabet = $request->alphabet;
        $class->class_fix = $request->class . ' ' . $major->major . ' ' . $request->alphabet;
        $class->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Kelas berhasil diedit'
        ], 201);
    }

    public function destroy($id)
    {
        $class = ClassTable::where('id', $id)->first();

        if(!$class) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        }

        $class->delete();

        return response()->json([], 204);
    }
}
