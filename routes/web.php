<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\saleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\reportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'homepage']);
Route::get('/dashboard', [DashboardController::class, 'dashboardPage'])->name('dashboardPage');
Route::get('/categoryPage', [CategoryController::class, 'categoryPage'])->name('categoryPage');
Route::get('/userRegistration', [UserController::class, 'userPage']);
Route::get('/userLogin', [UserController::class, 'LoginPage']);
Route::get('/resetPassword', [UserController::class, 'resetPage']);
Route::get('/sendotp', [UserController::class, 'sendOtpPage']);
Route::get('/verifyotp', [UserController::class, 'verifyOtpPage']);
Route::get('/userProfile', [UserController::class, 'profilePage']);
Route::get('/invoicePage', [InvoiceController::class, 'invoicePage'])->name('InvoicePage');
Route::get('/productPage', [ProductController::class, 'productPage'])->name('productPage');
Route::get('/customerPage', [customerController::class, 'customerPage'])->name('customerPage');
Route::get('/salePage', [saleController::class, 'salePage'])->name('salePage');
Route::get('/reportPage', [reportController::class, 'reportPage'])->name('reportPage');
