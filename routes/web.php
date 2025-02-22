<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactExportImportController;

Route::get('/', function () {
    return redirect('/login');
});

// Rute bawaan Laravel
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Contact
Route::get('/contact', [App\Http\Controllers\Master\ContactController::class, 'index'])->name('master.contact.index');
Route::get('/contact/create', [App\Http\Controllers\Master\ContactController::class, 'create'])->name('master.contact.create');
Route::post('/contact/store', [App\Http\Controllers\Master\ContactController::class, 'store'])->name('master.contact.store');
Route::delete('/contact/{id}', [App\Http\Controllers\Master\ContactController::class, 'destroy'])->name('master.contact.destroy');
Route::get('/contact/edit/{id}', [App\Http\Controllers\Master\ContactController::class, 'edit'])->name('master.contact.edit');
Route::get('/contact/show/{id}', [App\Http\Controllers\Master\ContactController::class, 'show'])->name('master.contact.show');
Route::put('/contact/update/{id}', [App\Http\Controllers\Master\ContactController::class, 'update'])->name('master.contact.update');

// Customer
Route::get('/customer', [App\Http\Controllers\Master\CustomerController::class, 'index'])->name('master.customer.index');

// Export & Import
Route::get('/contact/export-template', [ContactExportImportController::class, 'exportTemplate'])->name('contact.exportTemplate');
Route::post('/contact/import', [ContactExportImportController::class, 'import'])->name('contact.import');

// product
Route::get('/product', [App\Http\Controllers\Master\ProductController::class, 'index'])->name('master.product.index');
Route::post('/product/upload_property/{encodedId}', [App\Http\Controllers\Master\ProductController::class, 'upload_property'])->name('master.product.upload_property');