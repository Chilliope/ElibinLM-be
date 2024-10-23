<?php

namespace App\Http\Controllers;

use App\Models\ClassTable;
use App\Models\LibraryMember;
use App\Models\Major;
use App\Models\Visitor;
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

        if($major->id == 1) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Tidak bisa dihapus'
            ], 400);
        }

        $class = ClassTable::where('major_id', $major->id)->get();

        if($class->isNotEmpty()) {
            foreach($class as $class) {
                $class->delete();
            }
        }

        $member = LibraryMember::where('major_id', $major->id)->get();

        if($member->isNotEmpty()) {
            foreach($member as $member) {
                $member->major_id = 1;
                $member->save();
            }
        }

        $visitor = Visitor::where('major_id', $major->id)->get();

        if($visitor->isNotEmpty()) {
            foreach($visitor as $visitor) {
                $visitor->major_id = 1;
                $visitor->save();
            }
        }

        $major->delete();

        return response()->json([], 204);
    }
}
