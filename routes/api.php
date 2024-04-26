<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * Auth Route
 */
Route::post("/login", [AuthController::class, "login"]);
Route::get("/logout", [AuthController::class, "logout"])->middleware("auth:account_api");

/**
 * Customer Route
 */
Route::prefix("/customer")->group(function() {
    Route::post("/register",[CustomerController::class, "register"]);

    Route::get("/show", [CustomerController::class, "show"])->middleware("auth:account_api");
    Route::put("/update", [CustomerController::class, "update"])->middleware("auth:account_api");
});

/**
 * Category Route
 */

Route::prefix("/category")->group(function() {
    Route::get("/index", [CategoryController::class, "index"]);
    Route::get("/{id}", [CategoryController::class, "show"])->where('id', '[0-9]+');
    Route::get("/{id}/brands", [CategoryController::class, "brands"])->where('id', '[0-9]+');
});

/**
 * Product Route
 */

Route::prefix("/product")->group(function() {
    Route::get("/brand/{brand}", [ProductController::class, "brand"]);
    Route::get("/demands", [ProductController::class, "demands"]);
});

/**
 * Cart Route
 */

Route::prefix("/cart")->group(function() {
    Route::post("/addToCart", [CartController::class, "addToCart"]);
    Route::get("/", [CartController::class, 'index']);
    Route::put("/updateCart", [CartController::class, "updateCart"]);
    Route::delete("/removeCart", [CartController::class, "removeCart"]);
    Route::get("/checkout", [CartController::class, "checkout"])->middleware("auth:account_api");
});

/**
 * Order Route
 */

Route::prefix("/order")->group(function() {
    Route::post("/", [OrderController::class, "order"])->middleware("auth:account_api");
});

/**
 * Order Route
 */