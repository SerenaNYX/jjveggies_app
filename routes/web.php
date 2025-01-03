<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;

Route::get('/', [ProductController::class, 'welcomeProducts'])->name('welcome');


Route::get('/product', [ProductController::class, 'showProducts'])->name('product.show');

Route::get('/loginpage', function () { 
    return view('loginpage');
});

Route::get('/registerpage', function () { 
    return view('registerpage');
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [UserController::class, 'update'])->name('profile.update');
});


Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');


// check role (admin/staff/driver)

Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:employee', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::middleware(['auth:employee', 'role:staff'])->group(function () {
    Route::get('staff/dashboard', [AdminController::class, 'dashboard'])->name('staff.dashboard');
});

Route::prefix('staff')->name('staff.')->middleware('auth:employee', 'role:staff')->group(function () {
    Route::resource('products', \App\Http\Controllers\Staff\ProductController::class);
});


Route::middleware(['auth:employee', 'role:driver'])->group(function () {
    Route::get('driver/dashboard', [AdminController::class, 'dashboard'])->name('driver.dashboard');
});
