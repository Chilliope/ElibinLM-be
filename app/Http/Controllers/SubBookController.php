<?php

namespace App\Http\Controllers;

use App\Models\SubBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubBookController extends Controller
{
    public function index(Request $request, $bookId)
    {
        $subBook = SubBook::where('book_id', $bookId)
                          ->where('code', 'like', '%' . $request->search . '%')
                          ->with(['book'])
                            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $subBook
        ], 200);
    }

    public function show($id)
    {
        $subBook = SubBook::where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $subBook
        ], 200);
    }

    public function store(Request $request, $bookId)
    {
        $lastSubBook = SubBook::where('book_id', $bookId)->orderBy('copy', 'desc')->first();
        $lastCopyNumber = $lastSubBook ? $lastSubBook->copy : 0; // Jika belum ada subBook, mulai dari 0
        // dd($lastSubBook);
    
        // Loop untuk membuat subBook sesuai dengan jumlah stock yang diinginkan
        for ($i = 1; $i <= $request->stock; $i++) {
            // Membuat angka acak 3 digit untuk setiap subBook
            $randomNumber = rand(100, 999);
    
            // Menyusun format copy yang diinginkan
            $copy = $lastCopyNumber + $i;
            // dd($copy);
            $code = 'P' . $bookId . $randomNumber . $copy; // Format: P{book_id}{randomNumber}{copy}
    
            // Membuat subBook dengan copy dan code yang sudah disusun
            SubBook::create([
                'book_id' => $bookId,
                'copy' => $copy,
                'code' => $code
            ]);
            // Return response sukses
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Sub Book berhasil dibuat',
            // 'copy' => $copy // Mengembalikan informasi copy yang dibuat
        ], 201);

    }

    public function destroy(Request $request, $id)
    {
        $subBook = SubBook::where('id', $id)->first();

        if (!$subBook) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $subBook->delete();

        return response()->json([], 204);
    }
}
