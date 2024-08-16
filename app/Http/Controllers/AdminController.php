<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $admin = User::get();

        return response()->json([
            'status' => 'success',
            'data' => $admin
        ], 200);
    }

    public function show($id)
    {
        $admin = User::where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $admin
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'username' => 'required',
            'name' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $data = [
            'username' => $request->username,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ];

        $admin = User::create($data);

        return response()->json([
            'status' => 'success',
            'username' => $admin->username
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'username' => 'required',
            'name' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $admin = User::where('id', $id)->first();

        if(!$admin) {
            return response()->json([
                'status' => 'not found',
                'message' => 'admin tidak ditemukan'
            ], 403);
        }

        $admin->username = $request->username;
        $admin->name = $request->name;
        $admin->password = Hash::make($request->password);
        $admin->save();

        return response()->json([
            'status' => 'success'
        ], 201);
    }

    public function destroy($id)
    {
        $admin = User::where('id', $id)->first();

        if(!$admin) {
            return response()->json([
                'status' => 'not found',
                'message' => 'admin tidak ditemukan'
            ], 403);
        }

        $admin->delete();

        return response()->json([], 204);
    }
}
