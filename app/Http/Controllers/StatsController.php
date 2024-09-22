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
        $member = LibraryMember::with(['major'])
        ->get()
        ->groupBy('major.major')
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
        $visitor = Visitor::with(['major'])
        ->get()
        ->groupBy('major.major')
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
        $borrow = Borrower::with(['member'])
        ->get()
        ->groupBy('member.major.major')
        ->map(function ($group) {
            return [
                'count' => $group->count(),
                'borrower' => $group
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $borrow
        ], 200);
    }
}
