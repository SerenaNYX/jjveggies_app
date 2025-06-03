<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\StaffContactController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\EmployeeController as EmployeeController;
use App\Http\Controllers\Admin\OrderController as EmployeeOrderController;
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

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth', 'banned')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'banned', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('verification-link-sent', true);
})->middleware(['auth', 'banned', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'banned'])->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [UserController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'banned'])->group(function () {
    Route::get('/addresses', [AddressController::class, 'index'])->name('address.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('address.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('address.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('address.destroy');
});

Route::middleware(['auth', 'banned'])->group(function () {
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/enquiries', [ContactController::class, 'userEnquiries'])->name('enquiries.index');
    Route::get('/enquiries/{enquiry}', [ContactController::class, 'show'])->name('enquiries.show');
    Route::get('/enquiries/attachments/{attachment}/download', [ContactController::class, 'downloadAttachment'])->name('enquiries.attachment.download');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware(['auth', 'banned'])->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update'); // Update the quantity of an item in the cart

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/stripe/payment', [StripeController::class, 'payment'])->name('stripe.payment');
    Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
});

Route::middleware(['auth', 'banned'])->group(function () {
    // Customer Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
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
    
    Route::get('/admin/enquiries', [StaffContactController::class, 'index'])->name('admin.enquiries.index');
    Route::get('/admin/enquiries/{enquiry}', [StaffContactController::class, 'show'])->name('admin.enquiries.show');
    Route::patch('/admin/products/{product}/toggle-status', [EmployeeProductController::class, 'toggleStatus'])
        ->name('admin.products.toggle-status');
});


Route::middleware(['auth:employee', 'role:staff'])->group(function () {
    Route::get('staff/dashboard', [AdminController::class, 'dashboard'])->name('staff.dashboard');
    Route::resource('staff/products', EmployeeProductController::class, ['as' => 'staff']);
    Route::resource('staff/categories', CategoryController::class, ['as' => 'staff']);

    Route::get('/staff/enquiries', [StaffContactController::class, 'index'])->name('staff.enquiries.index');
    Route::get('/staff/enquiries/{enquiry}', [StaffContactController::class, 'show'])->name('staff.enquiries.show');
    Route::put('/staff/enquiries/{enquiry}', [StaffContactController::class, 'update'])->name('staff.enquiries.update');
    Route::patch('/staff/products/{product}/toggle-status', [EmployeeProductController::class, 'toggleStatus'])
        ->name('staff.products.toggle-status');
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


// EMPLOYEE ORDER MANAGEMENT

// For admin (view only)
Route::middleware(['auth:employee', 'role:admin'])->group(function () {
    Route::get('admin/orders', [EmployeeOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('admin/orders/{order}', [EmployeeOrderController::class, 'show'])->name('admin.orders.show');
});

// For staff (view and update to preparing/packed)
Route::middleware(['auth:employee', 'role:staff'])->group(function () {
    Route::get('staff/orders', [EmployeeOrderController::class, 'index'])->name('staff.orders.index');
    Route::get('staff/orders/{order}', [EmployeeOrderController::class, 'show'])->name('staff.orders.show');
    Route::post('staff/orders/{order}/status', [EmployeeOrderController::class, 'updateStatus'])
        ->name('staff.orders.update-status');
    Route::post('staff/orders/{order}/assign-driver', [EmployeeOrderController::class, 'assignDriver'])
        ->name('staff.orders.assign-driver');
});

// For driver (view and update to delivering/completed)
Route::middleware(['auth:employee', 'role:driver'])->group(function () {
    Route::get('driver/orders', [EmployeeOrderController::class, 'index'])->name('driver.orders.index');
    Route::get('driver/orders/{order}', [EmployeeOrderController::class, 'show'])->name('driver.orders.show');
    Route::post('driver/orders/{order}/status', [EmployeeOrderController::class, 'updateStatus'])
         ->name('driver.orders.update-status');
});


// Rewards and referrals
// Referral routes
Route::middleware(['auth', 'banned'])->group(function () {
    //Route::get('/referral/enter', [ReferralController::class, 'showEnterReferral'])->name('referral.enter');
    Route::post('/referral/process', [ReferralController::class, 'processReferral'])->name('referral.process');
    Route::post('/referral/generate', [ReferralController::class, 'generateReferralCode'])->name('referral.generate');

    Route::get('/referral/enter/{code}', [ReferralController::class, 'enterReferral'])->name('referral.enter');
    Route::get('/referral/scanner', [ReferralController::class, 'showScanner'])->name('referral.scanner');
    Route::post('/referral/process-scanned', [ReferralController::class, 'processScannedReferral'])->name('referral.process-scanned');
    // Voucher routes
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers/redeem', [VoucherController::class, 'redeem'])->name('vouchers.redeem');
    
    // Checkout voucher route
    Route::post('/checkout/apply-voucher', [CheckoutController::class, 'applyVoucher'])->name('checkout.apply-voucher');
    
    // Order completion route
    Route::post('/orders/{order}/complete', [OrderController::class, 'completeOrder'])->name('orders.complete');

    Route::get('/vouchers/available', [VoucherController::class, 'availableVouchers'])
        ->name('vouchers.available')
        ->middleware('auth', 'banned');
});


// Report generation
// For admin
Route::middleware(['auth:employee', 'role:admin'])->group(function () {
    Route::get('admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::post('admin/reports/generate', [ReportController::class, 'generate'])->name('admin.reports.generate');
});

// For staff
Route::middleware(['auth:employee', 'role:staff'])->group(function () {
    Route::get('staff/reports', [ReportController::class, 'index'])->name('staff.reports.index');
    Route::post('staff/reports/generate', [ReportController::class, 'generate'])->name('staff.reports.generate');
});