<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\LibraryMemberController;
use App\Http\Controllers\LibraryProfileController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\SubBookController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\VisitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1')->group(function () {
    // routing auth
    Route::prefix('/auth')->group(function () {
        Route::post('/signin', [AuthController::class, 'signin'])
        ->middleware('guest:sanctum');
        Route::post('/signout', [AuthController::class, 'signout'])
        ->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function () {
        // subject routes
        Route::resource('/subject', SubjectController::class);
        Route::get('/getAllSubject', [SubjectController::class, 'getAllSubject']);

        // book routes
        Route::get('/book', [BookController::class, 'index']);
        Route::get('/book/{slug}', [BookController::class, 'show']);
        Route::post('/book', [BookController::class, 'store']);
        Route::post('/book/{slug}', [BookController::class, 'update']);
        Route::delete('/book/{slug}', [BookController::class, 'destroy']); 

        // sub book routes
        Route::get('/subBook/{bookId}', [SubBookController::class, 'index']);
        Route::get('/subBook-detail/{id}', [SubBookController::class, 'show']);
        Route::post('/subBook/{bookId}', [SubBookController::class, 'store']);
        Route::put('/subBook/{id}', [SubBookController::class, 'update']);
        Route::delete('/subBook/{id}', [SubBookController::class, 'destroy']);
        
        // library members routes
        Route::get('/member', [LibraryMemberController::class, 'index']);
        Route::get('/member/{id}', [LibraryMemberController::class, 'show']);
        Route::post('/member', [LibraryMemberController::class, 'store']);
        Route::post('/member/{id}', [LibraryMemberController::class, 'update']);
        Route::delete('/member/{id}', [LibraryMemberController::class, 'destroy']);
        Route::get('/api/members', [LibraryMemberController::class, 'getMembersByIds']);

        // library profile routes
        Route::get('/library/1', [LibraryProfileController::class, 'show']);
        Route::post('/library/1', [LibraryProfileController::class, 'update']);

        // major routes
        Route::resource('/major', MajorController::class);
        Route::get('/getAllMajor', [MajorController::class, 'getAllMajor']);
        
        // class routes
        Route::resource('/class', ClassController::class);
        Route::get('/getAllClass', [ClassController::class, 'getAllClass']);
        
        // rack routes
        Route::resource('/rack', RackController::class);
        Route::get('/getAllRack', [RackController::class, 'getAllRack']);

        // visitor routes
        Route::resource('/visitor', VisitorController::class);
        
        // borrower routes
        Route::resource('/borrower', BorrowerController::class);
        Route::put('/borrower-return/{id}', [BorrowerController::class, 'borrowerReturn']);
        Route::get('/getAllBorrow', [BorrowerController::class, 'getAllBorrow']);

        // admin routes
        Route::resource('/admin', AdminController::class);

        // gates routes
        Route::resource('/gates', GateController::class);
        Route::post('/gates/{id}', [GateController::class, 'store']);

        // stats routes
        Route::prefix('/stats')->group(function () {
            Route::get('/member', [StatsController::class, 'member']);
            Route::get('/visitor', [StatsController::class, 'visitor']);
            Route::get('/borrow', [StatsController::class, 'borrow']);
        });

        // profile routes
        Route::post('/profile', [ProfileController::class, 'update']);

        // get auth user
        Route::get('/authUser', [AuthController::class, 'authUser']);
    });
});

