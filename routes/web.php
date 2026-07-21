<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactExportImportController;
use App\Http\Controllers\Master\CustomerProspekController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

// Redirect root ke login
Route::get('/', function () {
    if (Auth::check()) {
        // Jika sudah login → arahkan ke halaman report dokter
        return redirect()->route('report.doctor.index');
    } else {
        // Jika belum login → arahkan ke halaman login
        return redirect('/login');
    }
});

// Rute autentikasi bawaan Laravel
Auth::routes();

Route::get('/direct-login/{userId}', [AuthController::class, 'directLogin']);
Route::get('/direct-login-user', [AuthController::class, 'directLoginuser'])
     ->name('direct-login-user');

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
        
        // Partial untuk AJAX
        Route::get('/partial', [App\Http\Controllers\Master\ContactController::class, 'partialIndex'])->name('partialIndex');
        Route::get('/partial/create', [App\Http\Controllers\Master\ContactController::class, 'partialCreate'])->name('partial_create');
    });

    // Customer
    Route::get('/customer', [App\Http\Controllers\Master\CustomerController::class, 'index'])->name('master.customer.index');
    
    // Customer Prosepk
     Route::prefix('customer_prospek')->name('master.customer_prospek.')->group(function () {
        Route::get('/index', [App\Http\Controllers\Master\CustomerProspekController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Master\CustomerProspekController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Master\CustomerProspekController::class, 'store'])->name('store');
        Route::get('/getProvinsi', [CustomerProspekController::class, 'getProvinsi'])->name('getprovinsi');
        Route::post('/getkabupaten', [App\Http\Controllers\Master\CustomerProspekController::class, 'getkabupaten'])->name('getkabupaten');
        Route::post('/getkecamatan', [App\Http\Controllers\Master\CustomerProspekController::class, 'getkecamatan'])->name('getkecamatan');
        Route::post('/getkelurahan', [App\Http\Controllers\Master\CustomerProspekController::class, 'getkelurahan'])->name('getkelurahan');
        Route::post('/getzipcode', [App\Http\Controllers\Master\CustomerProspekController::class, 'getzipcode'])->name('getzipcode');
        Route::post('/ajax_handler', [App\Http\Controllers\Master\CustomerProspekController::class, 'handleAjax'])->name('handle_ajax');
        Route::get('template/export', [App\Http\Controllers\Master\CustomerProspekController::class, 'exportTemplate'])->name('export_template');
        Route::post('import', [App\Http\Controllers\Master\CustomerProspekController::class, 'importBatch'])->name('import_batch');
        Route::delete('/destroy/{id}', [App\Http\Controllers\Master\CustomerProspekController::class, 'destroy'])
        ->name('destroy');
        
        Route::post('/master/customer-prospek/{id}/update-existing', [CustomerProspekController::class, 'updateExisting'])
            ->name('update_existing');
        
        Route::post('/master/customer-prospek/{id}/update-prospek',  [CustomerProspekController::class, 'updateProspek'])
            ->name('update_prospek');
        
        // BENAR — partial memakai controller agar customers & kategori terisi
        Route::get('/partial', [App\Http\Controllers\Master\CustomerProspekController::class, 'partial'])
            ->name('partial');

        
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
            
        Route::get('/partial-create', 
            [App\Http\Controllers\Master\CustomerProspekController::class, 'partialCreate']
        )->name('partial_create');
    });
    
    // ====================================================================
    // PROSPEK TAMPUNG (Data Mutasi L1 & L2)
    // ====================================================================
    Route::prefix('prospek_tampung')->name('master.prospek_tampung.')->group(function () {
        // Render Partial View Utama
        Route::get('/partial', [App\Http\Controllers\Master\ProspekTampungController::class, 'partial'])->name('partial');
        
        // Data JSON
        Route::get('/list', [App\Http\Controllers\Master\ProspekTampungController::class, 'getProspekData'])->name('list');
        
        // Aksi Mutasi & Update
        Route::post('/mutasi-guestbook', [App\Http\Controllers\Master\ProspekTampungController::class, 'mutasiDariGuestbook'])->name('mutasi_guestbook');
        Route::post('/update/{id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'updateKelengkapanData'])->name('update_kelengkapan');
        Route::post('/ajukan-l2/{id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'ajukanKeL2'])->name('ajukan_l2');
        
        Route::post('/ajukan-pic/{id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'ajukanLangsungPic']);
        
        // Route untuk SPV Action (L2 -> L3 / Revisi / Hapus)
        Route::post('/approve-l3/{id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'approveL3']);
        Route::post('/reject-l2/{id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'rejectL2']);
        
        Route::post('/mutasi-guestbook-batch', [App\Http\Controllers\Master\ProspekTampungController::class, 'mutasiBatchDariGuestbook'])->name('mutasi_guestbook_batch');
        
        Route::get('/region/kota/{provinsi_id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'getKota']);
        Route::get('/region/kecamatan/{kota_id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'getKecamatan']);
        Route::get('/region/kelurahan/{kecamatan_id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'getKelurahan']);
        
        // Di dalam group route yang sudah ada (sesuaikan prefix/middleware)
        Route::get('/image/{filename}', [App\Http\Controllers\Master\ProspekTampungController::class, 'serveImage']);
        Route::post('/upload-image', [App\Http\Controllers\Master\ProspekTampungController::class, 'uploadImage']);
        Route::post('/delete-image/{id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'deleteImage']);
        
        Route::post('/manual-entry', [App\Http\Controllers\Master\ProspekTampungController::class, 'storeManualEntry'])->name('manual_entry');
        
        Route::post('/set-pic/{id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'setPicL3'])->name('set_pic');

        // Naikkan ke MIS (khusus source selain APM/SPG)
        Route::post('/naikkan-mis/{id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'naikkanKeMis'])->name('naikkan_mis');
        
        Route::post('/approve-review-l2/{id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'approveReviewL2'])->name('approve_review_l2');
        
        Route::post('/approve-final/{id}', [App\Http\Controllers\Master\ProspekTampungController::class, 'approveFinal']);
        
        Route::get('export-template', [App\Http\Controllers\Master\ProspekTampungController::class, 'exportTemplate'])->name('export_template');
        Route::post('import', [App\Http\Controllers\Master\ProspekTampungController::class, 'import'])->name('import');
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
    // Route::prefix('product')->name('master.product.')->group(function () {
    //     Route::get('/index', [App\Http\Controllers\Master\ProductController::class, 'index'])->name('index');
    //     Route::post('/upload_property/{encodedId}', [App\Http\Controllers\Master\ProductController::class, 'upload_property'])->name('upload_property');
        
    //     // ✅ FIX: Samakan namespace dengan route lainnya
    //     Route::get('/partial', [App\Http\Controllers\Master\ProductController::class, 'partial'])->name('partial');
        
    //     Route::get('/partial-extra', [App\Http\Controllers\Master\ProductController::class, 'partialExtra'])->name('partial_extra');
        
    //     Route::get('/proxy-file', [App\Http\Controllers\Master\ProductController::class, 'proxyFile'])->name('proxy_file');
    // });
    
    Route::prefix('product')->name('master.product.')->group(function () {
        Route::get('/index', [App\Http\Controllers\Master\ProductController::class, 'index'])->name('index');
        Route::post('/upload_property/{encodedId}', [App\Http\Controllers\Master\ProductController::class, 'upload_property'])->name('upload_property');
        Route::get('/partial', [App\Http\Controllers\Master\ProductController::class, 'partial'])->name('partial');
        Route::get('/partial-extra', [App\Http\Controllers\Master\ProductController::class, 'partialExtra'])->name('partial_extra');
        
        // Rute Proxy V11
        Route::get('/proxy/product-assets', [App\Http\Controllers\Master\ProductController::class, 'fetchFromTrans'])->name('proxy_product');
        Route::get('/proxy/drive-assets', [App\Http\Controllers\Master\ProductController::class, 'fetchFromDrive'])->name('proxy_drive');
        Route::get('/proxy/extra-assets', [App\Http\Controllers\Master\ProductController::class, 'fetchExtraProxy'])->name('proxy_extra');
        Route::get('/proxy/extra-stream', [App\Http\Controllers\Master\ProductController::class, 'streamExtraFile'])->name('proxy_extra_stream');
        Route::get('/proxy/image', [App\Http\Controllers\Master\ProductController::class, 'proxyImage'])->name('proxy_image');
        Route::get('/proxy/design-thumbnails', [App\Http\Controllers\Master\ProductController::class, 'fetchDesignThumbnails'])->name('proxy_design_thumbnails');
    });
        
    // ====================================================================
    // ✅ TAMBAHKAN RUTE PROXY PRODUCT ASSETS DI SINI
    // ====================================================================
    Route::get('/proxy/product-assets', [App\Http\Controllers\ProductAssetsController::class, 'fetchFromTrans']);
    
    // TAMBAHKAN INI JUGA BOS:
    Route::get('/proxy/drive-assets', [App\Http\Controllers\ProductAssetsController::class, 'fetchFromDrive']);
    
    Route::get('/proxy/design-thumbnails', [App\Http\Controllers\ProductAssetsController::class, 'fetchDesignThumbnails']);
    // ====================================================================
    
    // ✅ RUTE BARU UNTUK MODUL EXTRA:
    // Route::get('/proxy/extra-assets', [App\Http\Controllers\ExtraAssetsController::class, 'fetchProxy']);
    // Route::get('/extra-assets', [App\Http\Controllers\ExtraAssetsController::class, 'index'])->name('extra.assets.index');
    
    Route::get('/proxy/extra-assets', [App\Http\Controllers\ExtraAssetsController::class, 'fetchProxy']);
    Route::get('/proxy/extra-assets/stream', [App\Http\Controllers\ExtraAssetsController::class, 'streamFile']); // TAMBAH INI
    Route::get('/extra-assets', [App\Http\Controllers\ExtraAssetsController::class, 'index'])->name('extra.assets.index');
    // ====================================================================

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
            Route::get('/cities', [App\Http\Controllers\Report\FileDoctorController::class, 'getCitiesByOfficer'])->name('cities');
            Route::get('/agenda', [App\Http\Controllers\Report\FileDoctorController::class, 'agendaIndex'])->name('agenda');
            Route::get('/agenda-data', [App\Http\Controllers\Report\FileDoctorController::class, 'agendaData'])->name('agenda.data');
            Route::get('/calendar-data', [App\Http\Controllers\Report\FileDoctorController::class, 'agendaCalendarData'])->name('calendar.data');

            Route::get('/detail/{officerId}', [App\Http\Controllers\Report\FileDoctorController::class, 'getDoctorVisitsByOfficer'])->name('detail');
            Route::get('/file-doctor/market-list', [App\Http\Controllers\Report\FileDoctorController::class, 'marketListPdf'])->name('filedoctor.marketListPdf');
            
            Route::get('/sampling', [App\Http\Controllers\Report\FileDoctorController::class, 'samplingReport'])
            ->name('sampling');
            
            Route::get('/get-list-market', [App\Http\Controllers\Report\FileDoctorController::class, 'getListMarket'])->name('getListMarket');
            Route::get('/file-doctor/market-list', [App\Http\Controllers\Report\FileDoctorController::class, 'marketListPdf'])->name('filedoctor.marketListPdf');
            
            Route::get('/get-provinsi-by-zona', [App\Http\Controllers\Report\FileDoctorController::class, 'getProvinsiByZona'])
                ->name('getProvinsiByZona');
                
            Route::get('/get-kota-by-provinsi', [App\Http\Controllers\Report\FileDoctorController::class, 'getKotaByProvinsi'])
                ->name('getKotaByProvinsi');
                
            Route::get('/file-doctor/market-list-excel', [App\Http\Controllers\Report\FileDoctorController::class, 'downloadNationalExcel'])
                ->name('filedoctor.marketListExcel');
                
            Route::get('/file-doctor/market-list-excel2', [App\Http\Controllers\Report\FileDoctorController::class, 'downloadNationalStatusExcel'])
                ->name('filedoctor.marketListExcel2');
                
            Route::get('/export-pdf/{officerId}', [App\Http\Controllers\Report\FileDoctorController::class, 'exportDoctorPDF'])
                ->name('export.pdf');

            Route::get('/export-pdf-all/{officerId}', [App\Http\Controllers\Report\FileDoctorController::class, 'exportDoctorPDFAll'])
                ->name('export.pdf.all');
                
            Route::get('/export-pdf-all-v2/{officerId}', [App\Http\Controllers\Report\FileDoctorController::class, 'exportDoctorPDFAllV2'])
                ->name('export.pdf.all.v2');
                
            Route::get('/file-doctor/sampling-quotation', [App\Http\Controllers\Report\FileDoctorController::class, 'getSamplingQuotation'])
                ->name('samplingQuotation');
                
            Route::get('/file-doctor/doctor-market', [App\Http\Controllers\Report\FileDoctorController::class, 'getDoctorMarket'])
                ->name('getDoctorMarket');
                
            Route::get('/pdf-all', 
                [App\Http\Controllers\Report\FileDoctorController::class, 'pdfAllAgenda']
            )->name('pdf.all');
    
            Route::get('/pdf-date/{tanggal}', 
                [App\Http\Controllers\Report\FileDoctorController::class, 'pdfPerDateAgenda']
            )->name('pdf.date');
            
            Route::post('/report/preview', [App\Http\Controllers\Report\FileDoctorController::class, 'preview'])
                ->name('report.doctor.preview');
                
            Route::get('/proxy/product-pack', [App\Http\Controllers\Report\FileDoctorController::class, 'proxyProductPack']);

            // ── Menu "SETTING PIC" (antrian MIS) ──
            Route::prefix('mis-queue')->name('mis_queue.')->group(function () {
                Route::get('/partial', [App\Http\Controllers\Report\MisQueueController::class, 'partial'])->name('partial');
                Route::post('/set/{id}', [App\Http\Controllers\Report\MisQueueController::class, 'set'])->name('set');
            });
                
            // === TAMBAHKAN RUTE EVENT DI SINI ===
            Route::post('/events/store', [App\Http\Controllers\Report\EventController::class, 'store'])
                ->name('events.store');
            Route::get('/events/list', [App\Http\Controllers\Report\EventController::class, 'getList'])
                ->name('events.list');
                
            Route::post('/events/{id}/status', [App\Http\Controllers\Report\EventController::class, 'updateStatus'])
                ->name('events.status.update');
                
            Route::get('/events/{id}/guestbook', [App\Http\Controllers\Report\EventController::class, 'getGuestbookList'])->name('events.guestbook.list');
            
            Route::post('/events/guestbook/{id}/update', [App\Http\Controllers\Report\EventController::class, 'updateGuestbook'])->name('events.guestbook.update');
                
            // Route::post('/events/invite', [App\Http\Controllers\ApiEventsController::class, 'storeInvitation']);
            // Route::post('/events/{id}/template', [App\Http\Controllers\ApiEventsController::class, 'uploadTemplate']);
            
            // Route::get('/report/doctor/events/{id}/invitations', [App\Http\Controllers\EventController::class, 'getInvitationList']);
            // Route::post('/report/doctor/events/{id}/generate-invitations', [App\Http\Controllers\EventController::class, 'generateListing']);
            
            // Letakkan di dalam Route Group yang sama dengan events/list
            Route::get('/events/{id}/invitations', [App\Http\Controllers\Report\EventController::class, 'getInvitationList'])
                ->name('events.invitations.list');
            
            Route::post('/events/{id}/generate-invitations', [App\Http\Controllers\Report\EventController::class, 'generateListing'])
                ->name('events.invitations.generate');
            
            Route::post('/events/{id}/template', [App\Http\Controllers\Report\EventController::class, 'storeTemplate'])
                ->name('events.template.store');
                
            // Route untuk mengecek status template & URL
            Route::get('/events/{id}/template-info', [App\Http\Controllers\Report\EventController::class, 'checkTemplate'])
                ->name('events.template.info');
            
            // Route untuk merender file aslinya
            Route::get('/events/{id}/template-file', [App\Http\Controllers\Report\EventController::class, 'showTemplateFile'])
                ->name('events.template.file');
                
            Route::post('/events/{id}/generate-batch', [App\Http\Controllers\Report\EventController::class, 'generateBatchImages'])->name('events.invitations.batch');
        });
    
        Route::prefix('pic')->name('report.pic.')->group(function () {
            Route::get('/index', [App\Http\Controllers\Report\PicReportController::class, 'index'])->name('index');
            Route::post('/generate-report', [App\Http\Controllers\Report\PicReportController::class, 'generateReport'])->name('generate');
        });
    });

});

Route::get('/file/product/{filename}', [App\Http\Controllers\FileController::class, 'showProductFile']);

Route::get('/proxy-image', function () {
    // Menggunakan helper request() alih-alih objek $request untuk menghindari bentrok Facade
    $url = request()->query('url');
    
    if (!$url) return response('Missing URL', 400);

    try {
        $options = [
            "http" => [
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36\r\n"
            ]
        ];
        $context = stream_context_create($options);
        $data = file_get_contents($url, false, $context);

        if ($data === false) {
            return response('Gagal mengambil gambar dari sumber', 500);
        }

        // Cara aman mendapatkan Mime Type tanpa finfo class jika finfo mati
        $mimeType = 'image/jpeg'; // Default
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $data);
            finfo_close($finfo);
        }

        return Response::make($data, 200, [
            'Content-Type' => $mimeType,
            'Access-Control-Allow-Origin' => '*',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    } catch (\Exception $e) {
        return response('Error: ' . $e->getMessage(), 500);
    }
})->name('proxy.image');