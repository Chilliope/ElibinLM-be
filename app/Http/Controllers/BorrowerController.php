<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrower;
use App\Models\Gate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class BorrowerController extends Controller
{

    public function index(Request $request)
    {
        // Mulai query dasar
        $query = Borrower::with(['class', 'book']);

        // Jika ada fromDate dan toDate, tambahkan whereBetween
        if ($request->fromDate && $request->toDate) {
            // Set toDate ke akhir hari (23:59:59)
            $toDate = Carbon::parse($request->toDate)->endOfDay();

            // Tambahkan filter between
            $query->whereBetween('date_of_borrowing', [$request->fromDate, $toDate]);
        }

        // Eksekusi query dan dapatkan hasilnya
        $borrowings = $query->get();


        // Array untuk menyimpan data terformat
        $formattedBorrowings = [];

        foreach ($borrowings as $borrowing) {
            $code = $borrowing->borrowing_code;

            // Jika kode peminjaman belum ada, buat entry baru
            if (!isset($formattedBorrowings[$code])) {
                $formattedBorrowings[$code] = [
                    'name' => $borrowing->name,
                    'class' => $borrowing->class->class_fix,
                    'total' => $borrowing->total,
                    'date_borrow' => $borrowing->date_of_borrowing,
                    'date_return' => $borrowing->date_of_return,
                    'books' => [] // Tambahkan array kosong untuk daftar buku
                ];
            }

            // Tambahkan buku yang dipinjam ke dalam array 'books'
            $formattedBorrowings[$code]['books'][] = [
                'title' => $borrowing->book->title,
                'writer' => $borrowing->book->writer,
                'ISBN' => $borrowing->book->ISBN,
                'publication_year' => $borrowing->book->publication_year
            ];
        }

        // Konversi array menjadi collection untuk pagination
        $borrowingsCollection = collect($formattedBorrowings);

        // Ambil jumlah item per halaman, misalnya 10
        $perPage = 10;

        // Ambil halaman saat ini dari request (default halaman 1)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Potong collection berdasarkan halaman
        $currentPageItems = $borrowingsCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        // Buat LengthAwarePaginator secara manual
        $paginatedItems = new LengthAwarePaginator(
            $currentPageItems, // Data untuk halaman saat ini
            $borrowingsCollection->count(), // Total item dalam collection
            $perPage, // Jumlah item per halaman
            $currentPage, // Halaman saat ini
            ['path' => request()->url(), 'query' => request()->query()] // Tambahkan URL saat ini untuk pagination links
        );

        $borrowCount = Borrower::count();

        // Kembalikan response JSON yang telah dipaginate
        return response()->json([
            'status' => 'success',
            'count' => $borrowCount,
            'data' => $paginatedItems
        ]);
    }

    public function show($id)
    {
        $borrower = Borrower::where('id', $id)->with(['class', 'book'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $borrower
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'class_id' => 'required|exists:class,id',
            'date_of_borrowing' => 'required',
            'date_of_return' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        
        $getLastBorrowingCode = Borrower::latest()->first();
        
        if ($getLastBorrowingCode) {
            $borrowingCode = $getLastBorrowingCode->borrowing_code + 1;
        } else {
            $borrowingCode = 1;
        }
        
        $gate = Gate::get();
        // dd($gate);

        foreach ($gate as $gate) {
            $data = [
                'name' => $request->name,
                'class_id' => $request->class_id,
                'book_id' => $gate->book_id,
                'total' => $gate->total,
                'date_of_borrowing' => $request->date_of_borrowing,
                'date_of_return' => $request->date_of_return,
                'borrowing_code' => $borrowingCode
            ];

            $book = Book::where('id', $gate->book_id)->get();
            foreach($book as $book) {
                $book->stock = $book->stock - $gate->total;
                $book->save();
            }

            $borrower = Borrower::create($data);
        }

        $gate = Gate::truncate();

        return response()->json([
            'status' => 'success',
            'borrowing_code' => $borrower->borrowing_code
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'class_id' => 'required|exists:class,id',
            'book_id' => 'required|exists:books,id',
            'amount' => 'required',
            'date_of_borrowing' => 'required',
            'date_of_return' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $borrower = Borrower::where('id', $id)->first();

        if (!$borrower) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Peminjam tidak ditemukan'
            ], 404);
        }

        $borrower->update($request->all());

        return response()->json([
            'status' => 'success'
        ], 201);
    }

    public function destroy($code)
    {
        $borrower = Borrower::where('borrowing_code', $code)->get();

        if (!$borrower) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Peminjam tidak ditemukan'
            ], 404);
        }

        foreach($borrower as $borrower) {
            $book = Book::where('id', $borrower->book_id)->get();
            foreach($book as $book) {
                $book->stock = $book->stock + $borrower->total;
                $book->save();
            }

            $borrower->delete();
        }

        return response()->json([], 204);
    }
}
