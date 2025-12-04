<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua route di sini otomatis memiliki prefix /api.
| Contoh: /api/login, /api/posts, /api/categories
*/

// -----------------------------
// ROUTE PUBLIK
// -----------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// kategori bisa publik
Route::get('/categories', [CategoryController::class, 'index']);


// -----------------------------
// ROUTE TERPROTEKSI (HARUS LOGIN)
// -----------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD Posts
    Route::apiResource('/posts', PostController::class);

    // Info user yang login
    Route::get('/user', function (Request $request) {
        return response()->json([
            'id'       => $request->user()->id,
            'name'     => $request->user()->name,
            'email'    => $request->user()->email,
            'is_admin' => (bool) $request->user()->is_admin,
        ]);
    });
});
