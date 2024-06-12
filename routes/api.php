<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DescriptionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\StatisticalController;
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
    /**
     * Order Route
     */
    Route::prefix("/order")->group(function() {
        Route::post("/", [OrderController::class, "order"])->middleware("auth:account_api");
    });

    /**
     * Review Route
     */
    Route::prefix("/review")->group(function() {
        Route::post("/", [ProductReviewController::class, "create"]);
    })->middleware("auth:account_api");
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
 * Employee Route
 */
Route::prefix("/employee")->group(function(){
    Route::get("/order/pending", [OrderController::class, "getOrderPending"]);
    Route::put("/order/accept/{id}", [OrderController::class, "accept"])->where("id", "[0-9]+");
})->middleware("auth:account_api");

/**
 * Admin Route
 */
Route::prefix("/admin")->group(function() {
    /**
     * Quản lý nhân viên
     */
    Route::prefix("/employee")->group(function() {
        Route::get("/", [EmployeeController::class, "index"]);
        Route::post("/register",[EmployeeController::class, "register"]);
        Route::put("/update/{id}",[EmployeeController::class, "update"])->where("id", "[0-9]+");
        Route::delete("/delete/{id}",[EmployeeController::class, "delete"])->where("id", "[0-9]+");
        Route::get("/show/{id}",[EmployeeController::class, "show"])->where("id", "[0-9]+");
        Route::get("/search/{keyword}", [EmployeeController::class, "search"]);
    });
    /**
     * Quản lý sản phẩm
     */
    Route::prefix("/product")->group(function() {
        Route::get("/", [ProductController::class, "index"]);
        Route::post("/create", [ProductController::class, "create"]);
        Route::put("/update/{id}", [ProductController::class, "update"])->where("id", "[0-9]+");
        Route::get("/show/{id}", [ProductController::class, "show"])->where("id", "[0-9]+");
        Route::delete("/delete/{id}", [ProductController::class, "delete"])->where("id", "[0-9]+");
        Route::get("/search/{keyword}", [ProductController::class, "search"]);
        Route::get("/{filter}/{order}", [ProductController::class, "filter"]);
    });
    /**
     * Quản lý hình ảnh sản phẩm
     */
    Route::prefix("/image")->group(function() {
        Route::post("/create/{id}", [ImageController::class, "create"])->where("id", "[0-9]+");
        Route::delete("/delete/{id}", [ImageController::class, "delete"])->where("id", "[0-9]+");
        Route::get("/{id}", [ImageController::class, "index"])->where("id", "[0-9]+");
    });

    /**
     * Quản lý mô tả sản phẩm
     */
    Route::prefix("/description")->group(function() {
        Route::post("/create/{id}", [DescriptionController::class, "create"])->where("id", "[0-9]+");
        Route::put("/update/{id}", [DescriptionController::class, "update"])->where("id", "[0-9]+");
        Route::delete("/delete/{id}", [DescriptionController::class, "delete"])->where("id", "[0-9]+");
        Route::get("/{id}", [DescriptionController::class, "index"])->where("id", "[0-9]+");
    });

    /**
     * Quản lý khách hàng
     */
    Route::prefix("/customer")->group(function() {
        Route::delete("/delete/{id}", [CustomerController::class, "delete"])->where("id", "[0-9]+");
        Route::get("/", [CustomerController::class, "index"]);
        Route::get("/search/{keyword}", [CustomerController::class, "search"]);
        Route::get("/{filter}/{order}", [CustomerController::class, "filter"]);
    });

    /**
     * Quản lý Đơn hàng
     */
    Route::prefix("/order")->group(function() {
        Route::get("/", [OrderController::class, "index"]);
        Route::delete("/delete/{id}", [OrderController::class, "delete"])->where("id", "[0-9]+");
        Route::put("/update/{id}", [OrderController::class, "update"])->where("id", "[0-9]+");
        Route::get("/search/{keyword}", [OrderController::class, "search"]);
        Route::get("/show/{id}", [OrderController::class, "show"])->where("id", "[0-9]+");
    });

     /**
     * Quản lý đánh giá
     */
    Route::prefix("/review")->group(function() {
        Route::get("/", [ProductReviewController::class, "index"]);
        Route::delete("/delete/{id}", [ProductReviewController::class, "delete"])->where("id", "[0-9]+");
        Route::get("/search/{keyword}", [ProductReviewController::class, "search"]);
    });

    Route::prefix("/statistical")->group(function() {
        Route::get("/sumOrder", [StatisticalController::class, "sumOrder"]);
        Route::get("/sumProduct", [StatisticalController::class, "sumProduct"]);
        Route::get("/monthlyOrders/{year}", [StatisticalController::class, "monthlyOrders"]);
    });
})->middleware("auth:account_api");
