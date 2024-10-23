<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrower;
use App\Models\Gate;
use App\Models\LibraryMember;
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
        $query = Borrower::with(['member', 'book']);

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
            $member = LibraryMember::where('id', $borrowing->member_id)->first();

            // Jika kode peminjaman belum ada, buat entry baru
            if (!isset($formattedBorrowings[$code])) {
                $formattedBorrowings[$code] = [
                    'member_id' => $borrowing->member_id,
                    'book_title' => $borrowing->book_title,
                    'total' => $borrowing->total,
                    'date_borrow' => $borrowing->date_of_borrowing,
                    'date_return' => $borrowing->date_of_return,
                    'status' => $borrowing->status,
                    'books' => [], // Tambahkan array kosong untuk daftar buku
                    'member' => [
                        'name' => $borrowing->member->name,
                        'major' => $borrowing->member->major->major,
                    ]
                ];
            }

            // Tambahkan buku yang dipinjam ke dalam array 'books'
            $formattedBorrowings[$code]['books'][] = [
                'title' => $borrowing->book_title,
                // 'title' => $borrowing->book->title,
                // 'writer' => $borrowing->book->writer,
                // 'ISBN' => $borrowing->book->ISBN,
                // 'publication_year' => $borrowing->book->publication_year
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

    public function getAllBorrow(Request $request)
    {
        // Mulai query dasar
        $query = Borrower::with(['member', 'book']);

        // Jika ada fromDate dan toDate, tambahkan whereBetween
        if ($request->fromDate && $request->toDate) {
            // Set toDate ke akhir hari (23:59:59)
            $toDate = Carbon::parse($request->toDate)->endOfDay();

            // Tambahkan filter between
            $query->whereBetween('date_of_borrowing', [$request->fromDate, $toDate]);
        }

        // Eksekusi query dan dapatkan semua hasil tanpa paginasi
        $borrowings = $query->get();

        // Array untuk menyimpan data terformat
        $formattedBorrowings = [];

        foreach ($borrowings as $borrowing) {
            $code = $borrowing->borrowing_code;
            $member = LibraryMember::where('id', $borrowing->member_id)->first();

            // Jika kode peminjaman belum ada, buat entry baru
            if (!isset($formattedBorrowings[$code])) {
                $formattedBorrowings[$code] = [
                    'member_id' => $borrowing->member_id,
                    'total' => $borrowing->total,
                    'date_borrow' => $borrowing->date_of_borrowing,
                    'date_return' => $borrowing->date_of_return,
                    'status' => $borrowing->status,
                    'books' => [], // Tambahkan array kosong untuk daftar buku
                    'member' => [
                        'name' => $borrowing->member->name,
                        'major' => $borrowing->member->major->major,
                    ]
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

        // Hitung jumlah total peminjaman
        $borrowCount = Borrower::count();

        // Kembalikan response JSON tanpa pagination
        return response()->json([
            'status' => 'success',
            'count' => $borrowCount,
            'data' => $formattedBorrowings
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
            'member_id' => 'required',
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

        foreach ($gate as $gate) {
            // Ambil buku berdasarkan book_id
            $book = Book::find($gate->book_id);
            
            // Pastikan buku ditemukan sebelum mengakses title
            if ($book) {
                $data = [
                    'member_id' => $request->member_id,
                    // 'book_id' => $gate->book_id,
                    'book_title' => $book->title, // Mengambil title dari model Book
                    'total' => $gate->total,
                    'date_of_borrowing' => $request->date_of_borrowing,
                    'date_of_return' => $request->date_of_return,
                    'borrowing_code' => $borrowingCode
                ];
        
                $borrower = Borrower::create($data);
            } else {
                // Tangani kasus ketika book_id tidak ditemukan
                return response()->json(['error' => 'Book not found'], 404);
            }
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
            'member_id' => 'required',
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

        foreach ($borrower as $borrower) {
            $book = Book::where('id', $borrower->book_id)->get();
            foreach ($book as $book) {
                $book->stock = $book->stock + $borrower->total;
                $book->save();
            }

            $borrower->delete();
        }

        return response()->json([], 204);
    }

    public function borrowerReturn($id)
    {
        $borrower = Borrower::where('id', $id)->first();

        if (!$borrower) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $borrower->status = 'dikembalikan';
        $borrower->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Buku telah dikembalikan'
        ], 201);
    }
}
