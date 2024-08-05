<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LibraryProfileController extends Controller
{
    public function show()
    {
        $library = Library::where('id', 1)->first();

        return response()->json([
            'status' => 'success',
            'data' => $library
        ], 200);
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'library_number' => 'required',
            'library' => 'required',
            'school' => 'required',
            'address' => 'required',
            'subdistrict' => 'required',
            'city' => 'required',
            'province' => 'required',
            'post_code' => 'required',
            'phone' => 'required',
            'website' => 'required',
            'email' => 'required',
            'institutional_status' => 'required',
            'since' => 'required',
            'land_width' => 'required',
            'building_area' => 'required',
            'headmaster' => 'required',
            'head_librarian' => 'required',
            'vision' => 'required',
            'mission' => 'required',
            'short_history' => 'required',
            'opening_hours' => 'required',
            'library_activity' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $library = Library::where('id', 1)->first();

        if($request->file('image')) {
            if($library->image !== null) {
                Storage::delete($library->image);
            }

            $file = $request->file('image');
            $fileExt = $file->getClientOriginalExtension();
            $random = md5(uniqid(mt_rand(), true));                                                    

            $newFileName = $random . '.' . $fileExt;

            $file->move('storage/library-image', $newFileName);
            $library->image = 'library-image/' . $newFileName;
        }

        $library->library_number = $request->library_number;
        $library->library = $request->library;
        $library->school = $request->school;
        $library->address = $request->address;
        $library->subdistrict = $request->subdistrict;
        $library->city = $request->city;
        $library->province = $request->province;
        $library->post_code = $request->post_code;
        $library->phone = $request->phone;
        $library->website = $request->website;
        $library->email = $request->email;
        $library->institutional_status = $request->institutional_status;
        $library->since = $request->since;
        $library->land_width = $request->land_width;
        $library->building_area = $request->building_area;
        $library->headmaster = $request->headmaster;
        $library->head_librarian = $request->head_librarian;
        $library->vision = $request->vision;
        $library->mission = $request->mission;
        $library->short_history = $request->short_history;
        $library->opening_hours = $request->opening_hours;
        $library->library_activity = $request->library_activity;
        $library->save();

        return response()->json([
            'status' => 'success',
            'library' => $library->library
        ], 201);
    }
}
