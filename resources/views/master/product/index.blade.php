@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded-3">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item"><a href="#">Product</a></li>
            <li class="breadcrumb-item active" aria-current="page">Index</li>
        </ol>
    </nav>

    <!-- <div > -->

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                setTimeout(function () { // Memastikan eksekusi langsung setelah halaman siap
                    let errorMessages = {!! json_encode($errors->all()) !!};
                    let formattedErrors = errorMessages.join("<br>"); // Gabungkan semua error dalam satu pesan
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal!',
                        html: formattedErrors,
                        confirmButtonColor: '#d33'
                    });
                }, 100); // Jalankan setelah 100ms agar cepat muncul
            });
        </script>
    @endif
    
    <!-- Header & Button Tambah Produk -->
    {{--<div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Daftar Produk</h4>
        <a href="" class="btn btn-primary">
            <i class="fa fa-plus"></i> Tambah Produk
        </a>
    </div>--}}

    <!-- Table Produk -->
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <table class="table table-striped table-hover" id="product_table">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($products as $product)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $product->brand_name }}</td>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td class="text-center">
                            <!-- endcode productID -->
                            @php
                                $encodedId = base64_encode($product->id);
                            @endphp

                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $product->id }}">
                                <i class="fa fa-eye"></i>
                            </button>
                    
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#uploadModal-{{ $encodedId }}">
                                <i class="fa fa-upload"></i>
                            </button>

                            <!-- Modal Upload -->
                            <div class="modal fade" id="uploadModal-{{ $encodedId }}" tabindex="-1" aria-labelledby="uploadModalLabel-{{ $encodedId }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <form id="uploadForm-{{ $encodedId }}" 
                                        action="{{ route('master.product.upload_property', $encodedId) }}" 
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-content border-0 rounded-3 shadow-lg">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="fa fa-upload"></i> Upload Media Produk
                                                </h5>
                                                <button type="button" class="btn-close text-white close-modal" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Thumbnail (JPG, PNG)</label>
                                                            <input type="file" name="img_thumbnail" class="form-control" accept="image/*" onchange="previewImage(event, 'thumbnailPreview-{{ $encodedId }}')">
                                                            <img id="thumbnailPreview-{{ $encodedId }}" class="mt-2 img-fluid rounded shadow-sm d-none" style="max-height: 200px;">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Image HD (JPG, PNG)</label>
                                                            <input type="file" name="img_hd" class="form-control" accept="image/*" onchange="previewImage(event, 'hdPreview-{{ $encodedId }}')">
                                                            <img id="hdPreview-{{ $encodedId }}" class="mt-2 img-fluid rounded shadow-sm d-none" style="max-height: 200px;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Video Produk (MP4)</label>
                                                            <input type="file" name="video_product" class="form-control" accept="video/mp4">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Video Sosmed (MP4)</label>
                                                            <input type="file" name="video_sosmed" class="form-control" accept="video/mp4">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">
                                                    <i class="fa fa-times"></i> Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-cloud-upload-alt"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal Show -->
                            <div class="modal fade" id="detailModal-{{ $product->id }}" 
                                tabindex="-1" 
                                aria-labelledby="detailModalLabel-{{ $product->id }}">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailModalLabel-{{ $product->id }}">Detail Produk</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <!-- Gambar Thumbnail -->
                                                <div class="col-lg-6 text-center">
                                                    <h6>Image Thumbnail</h6>
                                                    @if ($product->image)
                                                        <a href="{{ $product->image_url }}" data-lightbox="product-{{ $product->id }}" data-title="Thumbnail">
                                                            <img src="{{ $product->image_url }}" class="img-fluid rounded shadow-sm" alt="Thumbnail" style="width: 50%;">
                                                        </a>
                                                    @else
                                                        <p class="text-muted">Tidak ada gambar</p>
                                                    @endif
                                                </div>

                                                <!-- Gambar HD -->
                                                <div class="col-lg-6 text-center">
                                                    <h6>Image Notes</h6>
                                                    @if ($product->image_hd)
                                                        <a href="{{ $product->image_hd_url }}" data-lightbox="product-{{ $product->id }}" data-title="Image HD">
                                                            <img src="{{ $product->image_hd_url }}" class="img-fluid rounded shadow-sm" alt="Image HD" style="width: 50%;">
                                                        </a>
                                                    @else
                                                        <p class="text-muted">Tidak ada gambar</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <!-- Video Produk -->
                                                <div class="col-lg-6 text-center">
                                                    <h6>Product Videos</h6>
                                                    @if ($product->video_product_url)
                                                        <video width="40%" controls>
                                                            <source src="{{ $product->video_product_url }}" type="video/mp4">
                                                            Browser Anda tidak mendukung video.
                                                        </video>
                                                    @else
                                                        <p class="text-muted">Tidak ada video</p>
                                                    @endif
                                                </div>

                                                <!-- Video Sosmed -->
                                                <div class="col-lg-6 text-center">
                                                    <h6>Social Media Videos</h6>
                                                    @if ($product->video_sosmed_url)
                                                        <video width="40%" controls class="video-popup">
                                                            <source src="{{ $product->video_sosmed_url }}" type="video/mp4">
                                                            Browser Anda tidak mendukung video.
                                                        </video>
                                                    @else
                                                        <p class="text-muted">Tidak ada video</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Aktifkan DataTables
        $('#product_table').DataTable({
            pageLength: 10,
        });

        // Inisialisasi Select2
        $('.select2').select2();
    });

    // Preview Gambar
    window.previewImage = function(event, previewId) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let img = document.getElementById(previewId);
                img.src = e.target.result;
                img.classList.remove("d-none");
            };
            reader.readAsDataURL(file);
        }
    };

    // Reset Modal
    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('input[type="file"]').val("");
        $(this).find('img').each(function () {
            if (!this.src.includes("storage")) { 
                $(this).addClass("d-none").attr("src", ""); 
            }
        });
        $(this).find('.progress').addClass("d-none");
        $(this).find('.progress-bar').css("width", "0%");
    });

    const section = document.querySelector("section"),
    mainVideo = document.querySelector(".main-video video"),
     videos = document.querySelectorAll(".videos"),
     close = document.querySelector(".close");

     for (var i = 0; i < videos.length; i++) {
       videos[i].addEventListener("click", (e)=>{
         const target = e.target;
         section.classList.add("active");
         target.classList.add("active");
         let src = document.querySelector(".videos.active video").src;
         mainVideo.src = src;

         close.addEventListener("click", ()=>{
           section.classList.remove("active");
           target.classList.remove("active");
           mainVideo.src = "";
         });
       });
     };
</script>
@endsection