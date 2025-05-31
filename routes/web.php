<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'homepage']);
Route::get('/dashboard', [DashboardController::class, 'dashboardPage'])->name('dashboardPage');
Route::post('/userRegistration', [UserController::class, 'userRegistration']);
Route::post('/userLogin', [UserController::class, 'userLogin']);
Route::post('/resetPassword', [UserController::class, 'resetPassword'])->middleware(TokenVerificationMiddleware::class);
Route::post('/sendotp', [UserController::class, 'sendOtp']);
Route::post('/verifyotp', [UserController::class, 'verifyOtp']);
Route::get('/userProfile', [UserController::class, 'profilePage']);
Route::get('/invoicePage', [InvoiceController::class, 'invoicePage'])->name('InvoicePage');
Route::get('/productPage', [ProductController::class, 'productPage'])->name('productPage');
Route::get('/salePage', [SaleController::class, 'salePage'])->name('salePage');
Route::get('/reportPage', [ReportController::class, 'reportPage'])->name('reportPage');

Route::get('/customerPage', [CustomerController::class, 'customerPage'])->name('customerPage');

// category routes
Route::get('/category-list', [CategoryController::class, 'categoryList'])->middleware(TokenVerificationMiddleware::class);
Route::post('/categoryCreate', [CategoryController::class, 'CategoryCreate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/categoryDelete', [CategoryController::class, 'CategoryDelete'])->middleware(TokenVerificationMiddleware::class);
Route::post('/categoryUpdate', [CategoryController::class, 'CategoryUpdate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/categoryById', [CategoryController::class, 'CategoryById'])->middleware(TokenVerificationMiddleware::class);

// customer
Route::post('/customerCreate', [CustomerController::class, 'CustomerCreate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/customer-id', [CustomerController::class, 'CustomerById'])->middleware(TokenVerificationMiddleware::class);
Route::post('/customerUpdate', [CustomerController::class, 'CustomerUpdate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/customerDelete', [CustomerController::class, 'CustomerDelete'])->middleware(TokenVerificationMiddleware::class);

// product
Route::post('/productCreate', [ProductController::class, 'ProductCreate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/productList', [ProductController::class, 'ProductList'])->middleware(TokenVerificationMiddleware::class);
Route::post('/productById', [ProductController::class, 'ProductById'])->middleware(TokenVerificationMiddleware::class);
Route::post('/productUpdate', [ProductController::class, 'ProductUpdate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/productDelete', [ProductController::class, 'ProductDelete'])->middleware(TokenVerificationMiddleware::class);


// invoice
Route::post('/invoiceCreate', [InvoiceController::class, 'InvoiceCreate'])->middleware(TokenVerificationMiddleware::class);

