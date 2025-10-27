@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item"><a href="{{ route('master.contact.find') }}">Find Contact</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Kontak: {{ $contact->name }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-user me-2"></i> Detail Informasi Kontak</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            @php
                                $imagePath = 'storage/superuser_assets/media/master/contact/' . $contact->image_ktp;
                                $imageUrl = $contact->image_ktp ? asset($imagePath) : null;
                            @endphp

                            <div class="mb-3">
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" 
                                         alt="Foto KTP {{ $contact->name }}" 
                                         class="rounded-circle shadow-lg mb-3" 
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center shadow-lg mb-3" 
                                         style="width: 150px; height: 150px; font-size: 4rem;">
                                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <h2>{{ $contact->name }}</h2>
                            <p class="text-muted fs-5">{{ $contact->position }}</p>
                            
                            <hr>

                            <div class="bg-light p-3 rounded text-start">
                                <h6 class="text-primary"><i class="fa fa-store me-2"></i> Toko Terkait</h6>
                                <p class="mb-1"><strong>Nama Toko:</strong> {{ $contact->customer->name ?? '-' }}</p>
                                <p class="mb-0"><strong>Lokasi:</strong> {{ $contact->customer->text_kota ?? '-' }}, {{ $contact->customer->text_provinsi ?? '-' }}</p>
                            </div>

                            <hr>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('master.contact.edit', $contact->id) }}" class="btn btn-warning">
                                    <i class="fa fa-edit me-2"></i> Edit Kontak
                                </a>
                                <form action="{{ route('master.contact.destroy', $contact->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Apakah Anda yakin ingin menghapus kontak ini?')">
                                        <i class="fa fa-trash me-2"></i> Hapus Kontak
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-8 pt-3 pt-md-0">
                            <h4 class="mb-4">Data Kontak & Identitas</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <label class="form-label text-muted small">Telepon</label>
                                        <p class="mb-0 fs-5">{{ $contact->phone }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <label class="form-label text-muted small">Email</label>
                                        <p class="mb-0 fs-5">{{ $contact->email ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <label class="form-label text-muted small">Tanggal Lahir (DOB)</label>
                                        <p class="mb-0 fs-5">{{ $contact->dob ? \Carbon\Carbon::parse($contact->dob)->format('d F Y') : '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <label class="form-label text-muted small">Alamat (Optional)</label>
                                        <p class="mb-0 fs-5">{{ $contact->address ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <label class="form-label text-muted small">No KTP</label>
                                        <p class="mb-0 fs-5">{{ $contact->ktp }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <label class="form-label text-muted small">No NPWP</label>
                                        <p class="mb-0 fs-5">{{ $contact->npwp ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <h4 class="mt-4 mb-3">Dokumen Identitas</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header bg-secondary text-white small">Image KTP</div>
                                        <div class="card-body p-2 text-center">
                                            @if($imageUrl)
                                                <a href="{{ $imageUrl }}" data-lightbox="document">
                                                    <img src="{{ $imageUrl }}" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                                </a>
                                            @else
                                                <p class="text-danger mt-3">Tidak ada file KTP ditemukan.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                     <div class="card h-100">
                                        <div class="card-header bg-secondary text-white small">Image NPWP</div>
                                        <div class="card-body p-2 text-center">
                                            @php
                                                $imageNpwpPath = 'storage/superuser_assets/media/master/contact/' . $contact->image_npwp;
                                                $imageNpwpUrl = $contact->image_npwp ? asset($imageNpwpPath) : null;
                                            @endphp
                                            @if($imageNpwpUrl)
                                                <a href="{{ $imageNpwpUrl }}" data-lightbox="document">
                                                    <img src="{{ $imageNpwpUrl }}" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                                </a>
                                            @else
                                                <p class="text-danger mt-3">Tidak ada file NPWP ditemukan.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Back --}}
            <div class="text-end">
                 <a href="{{ route('master.contact.find') }}" class="btn btn-danger">
                    <i class="fa fa-arrow-left me-2"></i> Back to Find Contact
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Script tambahan jika ada, tidak ada yang spesifik untuk saat ini.
</script>
@endsection