<?php

use Illuminate\Support\Facades\Route;

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
Route::put('/contact/update/{id}', [App\Http\Controllers\Master\ContactController::class, 'update'])->name('master.contact.update');

// Customer
Route::get('/customer', [App\Http\Controllers\Master\CustomerController::class, 'index'])->name('master.customer.index');