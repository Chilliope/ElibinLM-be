<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'username' => 'required',
            'name' => 'required',
            'image' => 'required',
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $auth = Auth::user();
        $user = User::where('id', $auth->id)->first();

        if($request->file('image')) {
            if($user->image !== 'user-picture/default.jpg') {
                Storage::delete($user->image);
            }

            $file = $request->file('image');
            $fileExt = $file->getClientOriginalExtension();
            $random = md5(uniqid(mt_rand(), true));                                                    

            $newFileName = $random . '.' . $fileExt;

            $file->move('storage/user-picture', $newFileName);
            $user->image = 'user-picture/' . $newFileName;
        }

        if($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->username = $request->username;
        $user->name = $request->name;
        $user->save();

        return response()->json([
            'status' => 'success'
        ], 201);
    }
}
