<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\LibraryMember;
use App\Models\Visitor;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function member() 
    {
        $member = LibraryMember::with(['class'])
        ->get()
        ->groupBy('class.class_fix')
        ->map(function ($group) {
            return [
                'count' => $group->count(),
                'members' => $group
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $member
        ], 200);
    }

    public function visitor()
    {
        $visitor = Visitor::with(['class'])
        ->get()
        ->groupBy('class.class_fix')
        ->map(function ($group) {
            return [
                'count' => $group->count(),
                'visitor' => $group
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $visitor
        ], 200);
    }

    public function borrow()
    {
        $borrow = Borrower::with(['class'])
        ->get()
        ->groupBy('class.class_fix')
        ->map(function ($group) {
            return [
                'count' => $group->count(),
                'visitor' => $group
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $borrow
        ], 200);
    }
}
