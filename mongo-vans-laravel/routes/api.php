<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::post('/userbytoken', [AuthController::class, 'userByToken']);

    //category auth
    Route::post('/categories', [CategoriesController::class, 'store']);
    Route::post('/categories/{id}', [CategoriesController::class, 'edit']);
    Route::delete('/categories/{id}', [CategoriesController::class, 'delete']);

    //user auth
    Route::get('/users', [UsersController::class, 'show']);
    Route::get('/users/search/{keyword}', [UsersController::class, 'searchByKeyword']);
});

//category
Route::get('/categories', [CategoriesController::class, 'show']);
Route::get('/categories/menu', [CategoriesController::class, 'showAll']);
Route::get('/categories/options', [CategoriesController::class, 'showIdName']);
Route::get('/categories/search/{keyword}', [CategoriesController::class, 'searchByKeyword']);
Route::get('/categories/get/{id}', [CategoriesController::class, 'searchByID']);

//auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login/google', [AuthController::class, 'loginGoogle']);

//product
Route::get('/products', [ProductsController::class, 'show']);
Route::get('/allproducts', [ProductsController::class, 'showAll']);
Route::get('/products/search/{keyword}', [ProductsController::class, 'searchByKeyword']);
Route::get('/products/get/{id}', [ProductsController::class, 'searchByID']);
//size
Route::get('/products/sizes/{id}', [ProductsController::class, 'showSizes']);
Route::get('/products/images/{id}', [ProductsController::class, 'showImages']);


//user


