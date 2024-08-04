<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signin(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if($validated->fails()) {
            return response()->json($validated->errors(), 401);
        }

        $user = User::where('username', $request->username)->first();

        if($user) {
            if(Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => 'success',
                    'user' => $user,
                    'token' => $token
                ], 201);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'password salah'
                ], 403);
            }
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'username tidak ditemukan'
            ], 403);
        }
    }

    public function signout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'logout success'
        ], 200);
    }
}
