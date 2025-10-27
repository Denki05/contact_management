<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactExportImportController;
use App\Http\Controllers\Master\CustomerProspekController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Rute autentikasi bawaan Laravel

Auth::routes();
Route::get('/direct-login/{userId}', [AuthController::class, 'directLogin']);

// Home

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



// Group route yang memerlukan autentikasi

Route::middleware(['auth'])->group(function () {



    // Contact

    Route::prefix('contact')->name('master.contact.')->group(function () {

        // Rute untuk New Contact (Pilih Customer)

        Route::get('/new', [App\Http\Controllers\Master\ContactController::class, 'newContact'])->name('new'); 

        // Rute untuk Find Contact (List Kontak)

        Route::get('/find', [App\Http\Controllers\Master\ContactController::class, 'index'])->name('find'); // Mengganti index lama



        // Rute Create (Input Contact) - Sekarang akan menerima customer_id

        Route::get('/create/{manage_id}', [App\Http\Controllers\Master\ContactController::class, 'create'])->name('create');

        

        Route::post('/store', [App\Http\Controllers\Master\ContactController::class, 'store'])->name('store');

        Route::delete('/{id}', [App\Http\Controllers\Master\ContactController::class, 'destroy'])->name('destroy');

        Route::get('/edit/{id}', [App\Http\Controllers\Master\ContactController::class, 'edit'])->name('edit');

        Route::get('/show/{id}', [App\Http\Controllers\Master\ContactController::class, 'show'])->name('show');

        Route::put('/update/{id}', [App\Http\Controllers\Master\ContactController::class, 'update'])->name('update');

    });



    // Customer

    Route::get('/customer', [App\Http\Controllers\Master\CustomerController::class, 'index'])->name('master.customer.index');

    

    // Customer Prosepk

     Route::prefix('customer_prospek')->name('master.customer_prospek.')->group(function () {

        Route::get('/index', [App\Http\Controllers\Master\CustomerProspekController::class, 'index'])->name('index');

        Route::get('/create', [App\Http\Controllers\Master\CustomerProspekController::class, 'create'])->name('create');

        Route::post('/store', [App\Http\Controllers\Master\CustomerProspekController::class, 'store'])->name('store');

        Route::post('/getkabupaten', [App\Http\Controllers\Master\CustomerProspekController::class, 'getkabupaten'])->name('getkabupaten');

        Route::post('/getkecamatan', [App\Http\Controllers\Master\CustomerProspekController::class, 'getkecamatan'])->name('getkecamatan');

        Route::post('/getkelurahan', [App\Http\Controllers\Master\CustomerProspekController::class, 'getkelurahan'])->name('getkelurahan');

        Route::post('/getzipcode', [App\Http\Controllers\Master\CustomerProspekController::class, 'getzipcode'])->name('getzipcode');

        Route::post('/ajax_handler', [App\Http\Controllers\Master\CustomerProspekController::class, 'handleAjax'])->name('handle_ajax');

        Route::get('template/export', [App\Http\Controllers\Master\CustomerProspekController::class, 'exportTemplate'])->name('export_template');

        Route::post('import', [App\Http\Controllers\Master\CustomerProspekController::class, 'importBatch'])->name('import_batch');

        Route::delete('/destroy/{id}', [App\Http\Controllers\Master\CustomerProspekController::class, 'destroy'])

        ->name('destroy');

        // Export PDF gabungan (existing + prospek)

        Route::get('/export-pdf', [App\Http\Controllers\Master\CustomerProspekController::class, 'exportPdf'])->name('export_pdf');

    

        // Export PDF khusus existing

        Route::get('/export-pdf-existing', [App\Http\Controllers\Master\CustomerProspekController::class, 'exportExistingPdf'])->name('export_pdf_existing');

    

        // Export PDF khusus prospek

        Route::get('/export-pdf-prospek', [App\Http\Controllers\Master\CustomerProspekController::class, 'exportProspekPdf'])->name('export_pdf_prospek');

        

        Route::get('export-status-template', [App\Http\Controllers\Master\CustomerProspekController::class, 'exportStatusTemplate'])->name('export_status_template');

        Route::post('import-status-update', [App\Http\Controllers\Master\CustomerProspekController::class, 'importStatusUpdate'])->name('import_status_update');


        // ✅ Route Normalisasi Nama
            Route::post('/normalize', 
                [App\Http\Controllers\Master\CustomerProspekController::class, 'normalized']
            )->name('normalize');
    });

    

    // Product Prospek

    Route::prefix('product_prospek')->name('master.product_prospek.')->group(function () {

        Route::get('/index', [App\Http\Controllers\Master\ProductProspekController::class, 'index'])->name('index');

        Route::post('/store', [App\Http\Controllers\Master\ProductProspekController::class, 'store'])->name('store');

        Route::get('/show/{id}', [App\Http\Controllers\Master\ProductProspekController::class, 'show'])->name('show');

        Route::put('/update/{id}', [App\Http\Controllers\Master\ProductProspekController::class, 'update'])->name('update'); 

        Route::get('/api/existing', [App\Http\Controllers\Master\ProductProspekController::class, 'getExistingProducts'])->name('api.existing');

    });



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

    

    // Route::prefix('existing')->name('orders.existing.')->group(function () {

    //     Route::get('/index', [App\Http\Controllers\Order\ExistingController::class, 'index'])->name('index');

    //     Route::get('/create/{step}/{brand}/{customer}/{type}/{indent}', [App\Http\Controllers\Order\ExistingController::class, 'create'])->name('create');

    //     Route::post('/store', [App\Http\Controllers\Order\ExistingController::class, 'store'])->name('store');

    //     Route::post('/get_product_pack', [App\Http\Controllers\Order\ExistingController::class, 'get_product_pack'])->name('get_product_pack');

    //     Route::get('/search_kontrak/{id}/{merek}', [App\Http\Controllers\Order\ExistingController::class, 'search_kontrak'])->name('search_kontrak');

    //     Route::post('/get_product_kontrak', [App\Http\Controllers\Order\ExistingController::class, 'get_product_kontrak'])->name('get_product_kontrak');

    //     Route::get('/edit/{id}', [App\Http\Controllers\Order\ExistingController::class, 'edit'])->name('edit');

    //     Route::put('/update/{id}', [App\Http\Controllers\Order\ExistingController::class, 'update'])->name('update');

    //     Route::get('/lanjutkan/{id}', [App\Http\Controllers\Order\ExistingController::class, 'lanjutkan'])->name('lanjutkan');

    //     Route::get('/print_so/{id}', [App\Http\Controllers\Order\ExistingController::class, 'print_so'])->name('print_so');

    // });

    

    Route::prefix('report')->group(function () {

        Route::prefix('doctor')->name('report.doctor.')->group(function () {

            Route::get('/index', [App\Http\Controllers\Report\FileDoctorController::class, 'index'])->name('index');

            Route::get('/excel-view/{officer}/{prov}/{kota}/{name}', [App\Http\Controllers\Report\FileDoctorController::class, 'viewExcel']);

            Route::get('/excel-data/{officer}/{prov}/{kota}/{name}', [App\Http\Controllers\Report\FileDoctorController::class, 'excelData'])->name('data');

            Route::get('/cities', [App\Http\Controllers\Report\FileDoctorController::class, 'getCitiesByOfficer'])->name('cities');\// routes/web.php

            Route::get('/agenda', [App\Http\Controllers\Report\FileDoctorController::class, 'agendaIndex'])->name('agenda');

            Route::get('/agenda-data', [App\Http\Controllers\Report\FileDoctorController::class, 'agendaData'])->name('agenda.data');

            Route::get('/detail/{customerId}', [App\Http\Controllers\Report\FileDoctorController::class, 'getDoctorByCustomer'])->name('detail');

            Route::get('/file-doctor/market-list', [App\Http\Controllers\Report\FileDoctorController::class, 'marketListPdf'])->name('filedoctor.marketListPdf');

            

            Route::get('/sampling', [App\Http\Controllers\Report\FileDoctorController::class, 'samplingReport'])

            ->name('sampling');

        });

    

        Route::prefix('pic')->name('report.pic.')->group(function () {

            Route::get('/index', [App\Http\Controllers\Report\PicReportController::class, 'index'])->name('index');

            Route::post('/generate-report', [App\Http\Controllers\Report\PicReportController::class, 'generateReport'])->name('generate');

        });

    });



});



Route::get('/file/product/{filename}', [App\Http\Controllers\FileController::class, 'showProductFile']);