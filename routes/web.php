<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Staff\ProductController as EmployeeProductController;
use App\Http\Controllers\Admin\EmployeeController as EmployeeController;

// Customer Routes
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


// Employee Routes
Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:employee', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('admin/products', EmployeeProductController::class, ['as' => 'admin']);
    Route::resource('admin/employees', EmployeeController::class, ['as' => 'admin']);
    
    Route::post('admin/customers/{user}/deny', [EmployeeController::class, 'denyCustomer'])->name('admin.customers.deny');
    Route::post('admin/customers/{user}/unban', [EmployeeController::class, 'unbanCustomer'])->name('admin.customers.unban');
});
/*
Route::middleware(['auth:employee', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('admin/products', EmployeeProductController::class, ['as' => 'admin']);
    Route::resource('admin/employees', EmployeeController::class, ['as' => 'admin']);
    Route::delete('admin/customers/{user}/deny', [EmployeeController::class, 'denyCustomer'])->name('admin.customers.deny');
    Route::post('admin/customers/{user}/unban', [EmployeeController::class, 'unbanCustomer'])->name('admin.customers.unban');
    Route::get('admin/employees/search', [EmployeeController::class, 'searchEmployees'])->name('admin.employees.search');
    Route::get('admin/customers/search', [EmployeeController::class, 'searchCustomers'])->name('admin.customers.search');
});*/




Route::middleware(['auth:employee', 'role:staff'])->group(function () {
    Route::get('staff/dashboard', [AdminController::class, 'dashboard'])->name('staff.dashboard');
    Route::resource('staff/products', EmployeeProductController::class, ['as' => 'staff']);
});

Route::middleware(['auth:employee', 'role:driver'])->group(function () {
    Route::get('driver/dashboard', [AdminController::class, 'dashboard'])->name('driver.dashboard');
});
