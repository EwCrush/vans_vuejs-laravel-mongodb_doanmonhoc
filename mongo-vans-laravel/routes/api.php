<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;

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
    Route::post('/users/changepassword', [UsersController::class, 'changePassword']);
    // Route::post('/userbytoken', [AuthController::class, 'userByToken']);

    //category auth
    Route::post('/categories', [CategoriesController::class, 'store']);
    Route::put('/categories/{id}', [CategoriesController::class, 'edit']);
    Route::delete('/categories/{id}', [CategoriesController::class, 'delete']);

    //user auth
    Route::get('/users', [UsersController::class, 'show']);
    Route::get('/users/search/{keyword}', [UsersController::class, 'searchByKeyword']);
    Route::post('/users', [UsersController::class, 'store']);
    Route::put('/users/setadmin/{id}', [UsersController::class, 'setAdmin']);
    Route::delete('/users/{id}', [UsersController::class, 'delete']);
    Route::get('/users/token', [UsersController::class, 'getUserFromToken']);
    Route::put('/users', [UsersController::class, 'editUser']);
    Route::post('/users/sendmail', [UsersController::class, 'sendMail']);

    //product auth
    Route::post('/products/sizes/{id}', [ProductsController::class, 'storeSize']);
    Route::delete('/products/sizes/{id}', [ProductsController::class, 'deleteSize']);
    Route::put('/products/sizes/{id}', [ProductsController::class, 'editSize']);
    Route::post('/products/images/{id}', [ProductsController::class, 'storeImage']);
    Route::delete('/products/images/{id}', [ProductsController::class, 'deleteImage']);
    Route::post('/products', [ProductsController::class, 'storeProduct']);
    Route::put('/products/{id}', [ProductsController::class, 'editProduct']);
    Route::delete('/products/{id}', [ProductsController::class, 'deleteProduct']);

    //comment auth
    Route::put('/comments/likes/{id}', [CommentsController::class, 'like']);
    Route::post('/comments/{id}', [CommentsController::class, 'storeComment']);
    Route::put('/comments/{id}', [CommentsController::class, 'editComment']);
    Route::delete('/comments/{id}', [CommentsController::class, 'deleteComment']);

    //cart auth
    Route::post('/carts', [CartController::class, 'addToCart']);
    Route::get('/carts', [CartController::class, 'showCart']);
    Route::delete('/carts', [CartController::class, 'deleteFromCart']);
    Route::put('/carts/consignee', [CartController::class, 'editConsignee']);
    Route::put('/carts', [CartController::class, 'editQuantity']);
    Route::post('/carts/order', [CartController::class, 'order']);

    //order auth
    Route::get('/orders', [OrderController::class, 'show']);
    Route::get('/orders/user', [OrderController::class, 'showWithUser']);
    Route::get('/orders/user/chart', [OrderController::class, 'showChartFromUser']);
    Route::delete('/orders/user/{id}', [OrderController::class, 'deleteOrder']);
    Route::get('/orders/get/{id}', [OrderController::class, 'getItemsByID']);
    Route::put('/orders/{id}', [OrderController::class, 'editOrder']);

    //notification auth
    Route::get('/notifications', [NotificationController::class, 'show']);
    Route::put('/notifications/{id}', [NotificationController::class, 'read']);
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
Route::get('/products/filter', [ProductsController::class, 'showByFilter']);
Route::get('/allproducts', [ProductsController::class, 'showAll']);
Route::get('/products/search/{keyword}', [ProductsController::class, 'searchByKeyword']);
Route::get('/products/get/{id}', [ProductsController::class, 'searchByID']);
Route::get('/products/categories/{id}', [ProductsController::class, 'searchByCategory']);
Route::get('/products/classic', [ProductsController::class, 'searchByClassic']);
//size
Route::get('/products/sizes/{id}', [ProductsController::class, 'showSizes']);
//image
Route::get('/products/images/{id}', [ProductsController::class, 'showImages']);


//user
Route::post('/users/sendmail', [UsersController::class, 'sendMail']);
Route::post('/users/sendotp', [UsersController::class, 'sendOTP']);
Route::post('/users/resetpassword', [UsersController::class, 'resetPassword']);

//comment
Route::get('/comments/{id}', [CommentsController::class, 'showAllComments']);



