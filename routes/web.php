<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactExportImportController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Rute autentikasi bawaan Laravel
Auth::routes();

// Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Group route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {

    // Contact
    Route::prefix('contact')->name('master.contact.')->group(function () {
        Route::get('/index', [App\Http\Controllers\Master\ContactController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Master\ContactController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Master\ContactController::class, 'store'])->name('store');
        Route::delete('/{id}', [App\Http\Controllers\Master\ContactController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{id}', [App\Http\Controllers\Master\ContactController::class, 'edit'])->name('edit');
        Route::get('/show/{id}', [App\Http\Controllers\Master\ContactController::class, 'show'])->name('show');
        Route::put('/update/{id}', [App\Http\Controllers\Master\ContactController::class, 'update'])->name('update');
    });

    // Customer
    Route::get('/customer', [App\Http\Controllers\Master\CustomerController::class, 'index'])->name('master.customer.index');

    // Product
    Route::prefix('product')->name('master.product.')->group(function () {
        Route::get('/index', [App\Http\Controllers\Master\ProductController::class, 'index'])->name('index');
        Route::post('/upload_property/{encodedId}', [App\Http\Controllers\Master\ProductController::class, 'upload_property'])->name('upload_property');
    });

    // Export & Import Contact
    Route::prefix('contact')->group(function () {
        Route::get('/export-template', [ContactExportImportController::class, 'exportTemplate'])->name('contact.exportTemplate');
        Route::post('/import', [ContactExportImportController::class, 'import'])->name('contact.import');
    });
});

Route::get('/file/product/{filename}', [App\Http\Controllers\FileController::class, 'showProductFile']);