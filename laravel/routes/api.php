<?php

use App\Http\Controllers\Api\CatalogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

// Public API endpoints (no authentication required for read operations)
Route::middleware('throttle:api')->prefix('v1')->group(function () {
    // Catalog endpoints
    Route::prefix('catalog')->group(function () {
        Route::get('/', [CatalogController::class, 'index']);
        Route::get('/search', [CatalogController::class, 'search']);
        Route::get('/statistics', [CatalogController::class, 'statistics']);
        Route::get('/find-by-isbn', [CatalogController::class, 'findByIsbn']);
        Route::get('/{collection}', [CatalogController::class, 'show']);
        Route::get('/{collection}/items', [CatalogController::class, 'items']);
    });
});

// Protected API endpoints (authentication required)
Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->group(function () {
    // Add protected endpoints here
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
