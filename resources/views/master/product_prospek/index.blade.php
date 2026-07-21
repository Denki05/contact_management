@extends('layouts.app')

@section('content')
<style>
/* Kontainer utama lebih luas, berikan sedikit padding atas */
.container-fluid {
    padding-top: 1.5rem !important;
}

/* Penyesuaian Tabel */
.table-product {
    border-collapse: separate;
    border-spacing: 0 10px; /* Jarak antar baris lebih nyaman */
    width: 100%;
    margin-top: 1rem;
}

/* Baris Produk (Main Row) */
.table-product tbody tr.main-row {
    cursor: pointer;
    background-color: #fff;
    /* Shadow lebih lembut dan modern */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); 
    border-radius: 8px; /* Sudut membulat pada setiap baris */
    transition: all 0.3s ease; /* Transisi untuk hover lebih halus */
    /* Pastikan baris terlihat sebagai satu kesatuan */
    display: table-row; 
}

/* Efek Hover yang lebih interaktif */
.table-product tbody tr.main-row:hover {
    background-color: #f0f4f7; /* Warna hover lebih terang */
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12); /* Shadow sedikit membesar */
    transform: translateY(-2px); /* Efek angkat minor */
}

/* Sel Tabel */
.table-product td {
    border: none !important;
    padding: 18px 20px !important; /* Padding lebih besar */
    vertical-align: middle;
    /* Agar border-radius pada tr bekerja dengan baik */
    border-radius: 8px; /* Terapkan pada td agar shadow/hover terlihat penuh */
}

/* Header Produk (Nama Produk) */
.product-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 700; /* Lebih tebal */
    font-size: 1.35rem; /* Ukuran lebih besar */
    color: #343a40; /* Warna teks lebih gelap */
}

/* Meta Data Produk (Brand, Searah) */
.product-meta {
    font-size: 0.9rem; /* Ukuran sedikit lebih besar */
    color: #6c757d; /* Warna abu-abu yang lebih tenang */
    margin-top: 5px;
}

/* Harga Produk */
.product-price {
    font-weight: 800; /* Sangat tebal */
    color: #198754; /* Ganti warna biru menjadi hijau (Success) untuk harga agar lebih menonjolkan nilai positif */
    margin-top: 8px;
    font-size: 1.1rem; /* Ukuran lebih besar */
    letter-spacing: 0.5px; /* Sedikit spasi antar huruf untuk keterbacaan */
}

/* Tombol Switch (PR/EX) */
.btn-switch {
    font-weight: 600 !important;
    padding: 6px 15px !important; /* Padding yang lebih baik */
    border-radius: 20px !important; /* Bentuk pil/rounded penuh */
}
.btn-switch.active {
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3); /* Shadow yang lebih halus */
}
.btn-outline-primary:hover {
    background-color: #0d6efd;
    color: #fff;
}

/* Sembunyikan thead karena hanya ada satu kolom 'Produk' */
.table-product thead {
    display: none; 
}

/* Styling DataTables Search Bar */
div.dataTables_wrapper div.dataTables_filter label {
    font-weight: 600;
    color: #495057;
}
div.dataTables_wrapper div.dataTables_filter input {
    border-radius: 0.5rem;
    border: 1px solid #ced4da;
    padding: 0.375rem 0.75rem;
    box-shadow: none;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
div.dataTables_wrapper div.dataTables_filter input:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>

<div class="container-fluid px-3">

    {{-- Alert sukses & error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-times-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header Kontrol: Tombol Aksi dan Switch PR/EX --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button type="button" class="btn btn-success" id="btnAddProduct" data-bs-toggle="modal" data-bs-target="#formModal">
                <i class="fa fa-plus me-1"></i> Tambah Produk
            </button>
        </div>
        <div class="d-flex gap-2">
            <button type="button" id="btnPR" class="btn btn-primary btn-switch active">PR</button>
            <button type="button" id="btnEX" class="btn btn-outline-primary btn-switch">EX</button>
        </div>
    </div>
    
    {{-- Container untuk DataTables Search Bar --}}
    <div id="product_table_filter_container" class="mb-3">
        {{-- Search bar akan dimuat di sini oleh JavaScript --}}
    </div> 

    <table class="table align-middle table-product" id="product_table">
        {{-- Thead dihapus untuk tampilan kartu --}}
        <tbody id="product_body">
            @foreach($products as $p)
            <tr class="main-row" data-id="{{ $p->id }}">
                <td>
                    <div class="product-header">
                        <span>{{ $p->kode }} - {{ $p->nama }}</span>
                        <i class="fa fa-chevron-right text-muted" style="font-size: 0.9rem;"></i>
                    </div>
                    <div class="product-meta mt-1">
                        <span class="me-3"><strong>Brand:</strong> {{ $p->brand ?? '-' }}</span>
                        <span><strong>Searah:</strong> {{ $p->searah ?? '-' }}</span>
                    </div>
                    <div class="product-price">
                        $ {{ number_format($p->harga, 0, ',', '.') }}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Container untuk DataTables Pagination --}}
    <div id="product_table_paginate_container" class="d-flex justify-content-center mt-4"></div>
</div>

{{-- resources/views/master/product_prospek/_form.blade.php (Modal Form) --}}
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="formModalLabel">
                    <i class="fa fa-box"></i> Tambah Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formProductProspek" method="POST" action="{{ route('master.product_prospek.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Produk</label>
                            <input type="text" name="kode" class="form-control" required autocomplete = "off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="nama" class="form-control" required autocomplete = "off">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Brand</label>
                            <input type="text" name="brand" class="form-control" autocomplete = "off">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Inspired By</label>
                            <input type="text" name="searah" class="form-control" autocomplete = "off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control" step="0.01" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let table = $('#product_table').DataTable({
        // Konfigurasi DataTables
        dom: 'rtp', // 'r' processing, 't' table, 'p' pagination. Hilangkan 'l' length change dan 'f' filter default
        ordering: false,
        searching: true, // Tetap aktifkan searching
        autoWidth: false,
        info: false,
        paging: true,
        
        // PERBAIKAN UNTUK MENGATASI ERROR 'mData'
        columns: [
            { "name": "Produk", "searchable": true, "orderable": false }
        ],
        
        // Konfigurasi untuk memindahkan elemen Search dan Paging
        initComplete: function () {
            // Pindahkan Search Bar ke div khusus
            $('#product_table_filter').appendTo('#product_table_filter_container');
            // Ganti label "Cari Produk:" agar lebih jelas
            $('#product_table_filter label').contents().filter(function(){
                return this.nodeType === 3; // Pilih node teks
            }).replaceWith('Cari Produk: ');
            $('#product_table_filter input').addClass('form-control'); // Tambahkan class Bootstrap
            
            // Pindahkan Pagination ke div khusus
            $('#product_table_paginate').appendTo('#product_table_paginate_container');
        },
        language: {
            search: "Cari Produk:", 
            zeroRecords: "Tidak ditemukan data produk yang sesuai.",
            emptyTable: "Belum ada data produk untuk ditampilkan.",
            paginate: {
                next: '<i class="fa fa-angle-right"></i>', // Ikon untuk Next
                previous: '<i class="fa fa-angle-left"></i>' // Ikon untuk Previous
            }
        }
    });

    // Switch PR/EX
    $('#btnPR').on('click', function() {
        $(this).addClass('btn-primary active').removeClass('btn-outline-primary');
        $('#btnEX').removeClass('btn-primary active').addClass('btn-outline-primary');
        location.reload(); 
    });

    $('#btnEX').on('click', function() {
        $(this).addClass('btn-primary active').removeClass('btn-outline-primary');
        $('#btnPR').removeClass('btn-primary active').addClass('btn-outline-primary');

        $.ajax({
            url: '{{ route("master.product_prospek.api.existing") }}',
            type: 'GET',
            success: function(res) {
                if (res.success) {
                    table.clear().draw();
                    let data = res.data;
                    data.forEach(p => {
                        let row = `
                            <tr class="main-row">
                                <td>
                                    <div class="product-header">
                                        <span>${p.product_code} - ${p.product_name}</span>
                                        <i class="fa fa-chevron-right text-muted" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <div class="product-meta mt-1">
                                        <span class="me-3"><strong>Brand:</strong> ${p.brand_name ?? '-'}</span>
                                        <span><strong>Searah:</strong> ${p.searah_name ?? '-'}</span>
                                    </div>
                                    <div class="product-price">
                                        </div>
                                </td>
                            </tr>`;
                        table.row.add($(row)).draw(false); 
                    });
                    table.draw();
                } else {
                    alert(res.message || 'Gagal memuat data EX.');
                }
            },
            error: function() {
                alert('Gagal mengambil data API.');
            }
        });
    });

    // Tambah / Edit
    $('#btnAddProduct').on('click', function() {
        $('#formProductProspek')[0].reset();
        $('#formModalLabel').html('<i class="fa fa-box"></i> Tambah Produk');
        $('#formProductProspek').attr('action', "{{ route('master.product_prospek.store') }}");
        $('input[name="_method"]').remove();
    });

    $('#product_table').on('click', '.main-row', function() {
        const id = $(this).data('id');
        if ($('#btnEX').hasClass('active')) {
            return; 
        }

        $('#formModalLabel').html('<i class="fa fa-edit"></i> Edit Produk');
        $('#formProductProspek')[0].reset();

        if ($('input[name="_method"]').length === 0) {
            $('#formProductProspek').append('<input type="hidden" name="_method" value="PUT">');
        }

        $('#formProductProspek').attr('action', '/product_prospek/update/' + id);

        $.ajax({
            url: '/product_prospek/show/' + id,
            type: 'GET',
            success: function(res) {
                if (res.success) {
                    const p = res.data;
                    $('input[name="kode"]').val(p.kode);
                    $('input[name="nama"]').val(p.nama);
                    $('input[name="searah"]').val(p.searah);
                    $('input[name="harga"]').val(p.harga);
                    $('input[name="brand"]').val(p.brand);
                    $('input[name="kategori"]').val(p.kategori);
                    $('#formModal').modal('show');
                }
            },
            error: function() {
                alert('Gagal mengambil data produk.');
            }
        });
    });

    // Submit form
    $('#formProductProspek').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const formData = form.serialize();

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            success: function(res) {
                if (res.success) {
                    $('#formModal').modal('hide');
                    location.reload();
                } else {
                    let msg = res.message || 'Gagal menyimpan data.';
                    alert(msg);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += ' ' + xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    });
});
</script>
@endsection