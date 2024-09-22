<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrower;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Ambil buku berdasarkan pencarian
        $books = Book::where('title', 'like', '%' . $request->search . '%')
            ->with(['rack', 'subBook'])
            ->withCount('subBook')
            ->paginate(10);
    
        $bookCount = Book::count();
    
        return response()->json([
            'status' => 'success',
            'count' => $bookCount,
            'data' => $books
        ], 200);
    }    

    public function show($slug)
    {
        $book = Book::where('slug', $slug)
                    ->with(['rack'])
                    ->first();

        return response()->json([
            'status' => 'success',
            'data' => $book
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'writer' => 'required',
            'publisher' => 'required',
            'ISBN' => 'required',
            'publication_year' => 'required',
            'page_size' => 'required',
            'information' => 'required',
            'image' => 'required',
            'rack_id' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $bookExisting = Book::where('title', $request->title)->exists();

        if($bookExisting) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Judul buku sudah terdaftar dikoleksi'
            ], 400);
        }
        
        if($request->file('image')) {
            $file = $request->file('image');
            $fileExt = $file->getClientOriginalExtension();
            $random = md5(uniqid(mt_rand(), true));                                                    

            $newFileName = $random . '.' . $fileExt;

            $file->move('storage/book-image', $newFileName);
        }

        $subjectId = $request->subject_id ?? null;

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'writer' => $request->writer,
            'publisher' => $request->publisher,
            'ISBN' => $request->ISBN,
            'publication_year' => $request->publication_year,
            'page_size' => $request->page_size,
            'information' => $request->information,
            'image' =>  'book-image/' . $newFileName,
            'rack_id' => $request->rack_id,
            'subject_id' => $subjectId
        ];

        $book = Book::create($data);

        return response()->json([
            'status' => 'success',
            'book' => $book->slug
        ], 201);
    }

    public function update(Request $request, $slug)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'publisher' => 'required',
            'publication_year' => 'required',
            'page_size' => 'required',
            'information' => 'required',
            'rack_id' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $bookExisting = Book::where('title', $request->title)
                            ->where('slug', '!=', $slug)
                            ->exists();

        if($bookExisting) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Judul buku sudah terdaftar dikoleksi'
            ], 400);
        }

        $book = Book::where('slug', $slug)->first();

        if(!$book) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Koleksi tidak ditemukan'
            ], 404);
        }

        if($request->file('image')) {
            // hapus gambar yang lama
            Storage::delete($book->image);

            // upload gambar buku yang baru
            $file = $request->file('image');
            $fileExt = $file->getClientOriginalExtension();
            $random = md5(uniqid(mt_rand(), true));                                                    

            $newFileName = $random . '.' . $fileExt;
            
            $file->move('storage/book-image', $newFileName);
            $book->image = 'book-image/' . $newFileName;
        }

        $subjectId = $request->subject_id ?? null;

        $book->title = $request->title;
        $book->slug = Str::slug($request->title);   
        $book->publisher = $request->publisher;
        $book->publication_year = $request->publication_year;
        $book->page_size = $request->page_size;
        $book->information = $request->information;
        $book->rack_id = $request->rack_id;
        $book->subject_id = $subjectId;
        $book->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data koleksi buku berhasil di edit'
        ], 201);
    }

    public function destroy($slug)
    {
        $book = Book::where('slug', $slug)->first();

        if(!$book) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Koleksi tidak ditemukan'
            ], 404);
        }
        
        Storage::delete($book->image); // hapus gambar buku dari storage
        $book->delete();

        return response()->json([], 204);
    }
}
