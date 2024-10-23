<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subject = Subject::where('subject', 'like', '%' . $request->search . '%')->paginate(10);
        $subjectCount = Subject::count();

        return response()->json([
            'status' => 'success',
            'count' => $subjectCount,
            'data' => $subject
        ], 200);
    }

    public function getAllSubject()
    {
        $subject = Subject::get();

        return response()->json([
            'status' => 'success',
            'data' => $subject
        ], 200);
    }

    public function show($id)
    {
        $subject = Subject::where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $subject
        ], 200);
    }

    public function store(Request $request)
    {
        $subject = Subject::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Subject berhasil dibuat'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::where('id', $id)->first();

        if(!$subject) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Subjek tidak ditemukan'
            ], 404);
        }

        $subject->subject = $request->subject;
        $subject->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Subject berhasil diedit'
        ], 201);
    }

    public function destroy($id)
    {
        $subject = Subject::where('id', $id)->first();

        if($subject->id == 1) {
            return response()->json([
                'status' => 'failed',
                'message' => 'subjek ini tidak bisa dihapus'
            ], 400);
        } 

        $books = Book::where('subject_id', $subject->id)->get();

        if($books) {
            foreach ($books as $book) {
                $book->subject_id = 1;
                $book->save();
            }
        }

        $subject->delete();

        return response()->json([], 204);
    }
}
