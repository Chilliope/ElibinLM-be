<?php

namespace App\Http\Controllers;

use App\Models\ClassTable;
use App\Models\LibraryMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LibraryMemberController extends Controller
{
    public function index(Request $request)
    {
        $member = LibraryMember::where('name', 'like', '%' . $request->search . '%')->with(['class'])->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $member
        ], 200);
    }

    public function show($id) 
    {
        $member = LibraryMember::with(['class'])
                              ->where('id', $id)
                              ->first();

        return response()->json([
            'status' => 'success',
            'data' => $member
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'NIS' => 'required',
            'name' => 'required',
            'place_of_birth' => 'required',
            'date_of_birth' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'class_id' => 'required',
            'image' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 404);
        }

        $classCheck = ClassTable::where('id', $request->class_id)->exists();

        if(!$classCheck) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        }

        if($request->file('image')) {
            $file = $request->file('image');
            $fileExt = $file->getClientOriginalExtension();
            $random = md5(uniqid(mt_rand(), true));                                                    

            $newFileName = $random . '.' . $fileExt;

            $file->move('storage/member-image', $newFileName);
        }

        $data = [
            'NIS' => $request->NIS,
            'name' => $request->name,
            'place_of_birth' => $request->place_of_birth,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'address' => $request->address,
            'class_id' => $request->class_id,
            'image' => 'member-image/' . $newFileName
        ];

        $member = LibraryMember::create($data);

        return response()->json([
            'status' => 'success',
            'NIS' => $member->NIS
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'NIS' => 'required',
            'name' => 'required',
            'place_of_birth' => 'required',
            'date_of_birth' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'class_id' => 'required',
            'image' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 404);
        }

        $classCheck = ClassTable::where('id', $request->class_id)->exists();

        if(!$classCheck) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        }

        $member = LibraryMember::where('id', $id)->first();

        if(!$member) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Anggota Perpustakaan tidak ditemukan'
            ], 404);
        }

        if($request->file('image')) {
            Storage::delete($member->image);

            $file = $request->file('image');
            $fileExt = $file->getClientOriginalExtension();
            $random = md5(uniqid(mt_rand(), true));                                                    

            $newFileName = $random . '.' . $fileExt;

            $file->move('storage/member-image', $newFileName);
            $member->image = 'member-image/' . $newFileName;
        }

        $member->NIS = $request->NIS;
        $member->name = $request->name;
        $member->place_of_birth = $request->place_of_birth;
        $member->date_of_birth = $request->date_of_birth;
        $member->phone = $request->phone;
        $member->address = $request->address;
        $member->class_id = $request->class_id;
        $member->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Anggota Perpustakaan berhasil diedit'
        ]);
    }

    public function destroy($id)
    {
        $member = LibraryMember::where('id', $id)->first();

        if(!$member) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Anggota Perpustakaan tidak ditemukan'
            ], 404);
        }

        Storage::delete($member->image);
        $member->delete();

        return response()->json([], 204);
    }
}
