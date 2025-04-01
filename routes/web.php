<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\EmployeeController as EmployeeController;
use App\Http\Controllers\Staff\ProductController as EmployeeProductController;

// Customer Routes
Route::get('/', [ProductController::class, 'welcomeProducts'])->name('welcome');
Route::get('/product', [ProductController::class, 'showProducts'])->name('product.show');
Route::get('/search', [ProductController::class, 'search'])->name('product.search');
//Route::get('/products/{id}/options', [ProductController::class, 'getOptions'])->name('product.options');
Route::get('/products/{id}/options', [ProductController::class, 'getOptions']);

Route::get('/products/autocomplete', [ProductController::class, 'autocomplete'])->name('product.autocomplete');

Route::get('/login', function () {
    return view('loginpage');
})->name('login');
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/registerpage', function () { 
    return view('registerpage');
});
Route::post('/register', [UserController::class, 'register']);


// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


/*
// Email Verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return view('auth.verify-success');
 //   return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/verify-success', function () {
    return view('auth.verify-success');
})->name('verify.success');


Route::post('/email/verification-notification', function (Request $request) {
//    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
*/ 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [UserController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/addresses', [AddressController::class, 'index'])->name('address.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('address.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('address.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('address.destroy');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update'); // Update the quantity of an item in the cart


    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('stripe/payment', [StripeController::class, 'payment'])->name('stripe.payment');
    Route::get('stripe/payment/success', [StripeController::class, 'success'])->name('stripe.payment.success');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
});


// Employee Routes
Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::delete('/staff/products/options/{option}', [EmployeeProductController::class, 'deleteOption'])->name('staff.products.options.delete');

Route::middleware(['auth:employee', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('admin/products', EmployeeProductController::class, ['as' => 'admin']);
    Route::resource('admin/employees', EmployeeController::class, ['as' => 'admin']);
    Route::delete('/staff/products/options/{option}', [EmployeeProductController::class, 'deleteOption'])->name('staff.products.options.delete');
    Route::resource('admin/categories', CategoryController::class, ['as' => 'admin']);

    Route::post('admin/customers/{user}/deny', [EmployeeController::class, 'denyCustomer'])->name('admin.customers.deny');
    Route::post('admin/customers/{user}/unban', [EmployeeController::class, 'unbanCustomer'])->name('admin.customers.unban');
});


Route::middleware(['auth:employee', 'role:staff'])->group(function () {
    Route::get('staff/dashboard', [AdminController::class, 'dashboard'])->name('staff.dashboard');
    Route::resource('staff/products', EmployeeProductController::class, ['as' => 'staff']);
    Route::resource('staff/categories', CategoryController::class, ['as' => 'staff']);
});

Route::middleware(['auth:employee', 'role:driver'])->group(function () {
    Route::get('driver/dashboard', [AdminController::class, 'dashboard'])->name('driver.dashboard');
});

Route::get('/messages', function () { 
    return view('message');
});

Route::get('/about', function () { 
    return view('about');
});