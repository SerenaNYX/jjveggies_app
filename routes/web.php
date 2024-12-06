<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

/*Route::get('/', function () {
    return view('welcome');
});*/
//Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/', [ProductController::class, 'welcomeProducts'])->name('welcome');


Route::get('/product', function () {
    return view('product');
});

Route::get('/loginpage', function () { 
    return view('loginpage');
});

Route::get('/registerpage', function () { 
    return view('registerpage');
});

Route::get('/index', function () { 
    return view('index');
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [UserController::class, 'update'])->name('profile.update');
});

/*Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::resource('products', ProductController::class);
});*/

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/product', [ProductController::class, 'showProducts'])->name('product.show');
