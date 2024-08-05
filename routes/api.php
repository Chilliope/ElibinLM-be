<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\LibraryMemberController;
use App\Http\Controllers\LibraryProfileController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RackController;
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
        // book routes
        Route::get('/book', [BookController::class, 'index']);
        Route::get('/book/{slug}', [BookController::class, 'show']);
        Route::post('/book', [BookController::class, 'store']);
        Route::post('/book/{slug}', [BookController::class, 'update']);
        Route::delete('/book/{slug}', [BookController::class, 'destroy']); 
        
        // library members routes
        Route::get('/member', [LibraryMemberController::class, 'index']);
        Route::get('/member/{id}', [LibraryMemberController::class, 'show']);
        Route::post('/member', [LibraryMemberController::class, 'store']);
        Route::post('/member/{id}', [LibraryMemberController::class, 'update']);
        Route::delete('/member/{id}', [LibraryMemberController::class, 'destroy']);

        // library profile routes
        Route::get('/library/1', [LibraryProfileController::class, 'show']);
        Route::post('/library/1', [LibraryProfileController::class, 'update']);

        // major routes
        Route::resource('/major', MajorController::class);
        
        // class routes
        Route::resource('/class', ClassController::class);
        
        // rack routes
        Route::resource('/rack', RackController::class);
        
        // visitor routes
        Route::resource('/visitor', VisitorController::class);
        
        // borrower routes
        Route::resource('/borrower', BorrowerController::class);

        // profile routes
        Route::post('/profile', [ProfileController::class, 'update']);

        // get auth user
        Route::post('/authUser', [AuthController::class, 'authUser']);
    });
});

