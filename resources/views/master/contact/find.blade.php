@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-12">
            
            
            <form method="GET" action="{{ route('master.contact.find') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="store_id">Filter Toko</label>
                            <select name="store_id" class="form-control js-select2">
                                <option value="">-- Pilih Toko --</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }} - {{ $store->text_kota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                         <div class="form-group">
                            <label for="search">Cari Nama Kontak</label>
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Nama" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm me-2">
                            <i class="fa fa-search"></i> Cari
                        </button>
                        <a href="{{ route('master.contact.find') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <h4 class="mb-3">Daftar Kontak (Total: {{ $contacts->total() ?? 0 }})</h4>

            <div class="row">
                @forelse($contacts as $contact)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-start border-primary border-4 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        @php
                                            $imagePath = 'storage/superuser_assets/media/master/contact/' . $contact->image_ktp;
                                            $imageUrl = $contact->image_ktp ? asset($imagePath) : null;
                                        @endphp
            
                                        @if($imageUrl && file_exists(public_path($imagePath)))
                                            <img src="{{ $imageUrl }}" 
                                                 alt="{{ $contact->name }}" 
                                                 class="rounded-circle border"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center border"
                                                 style="width: 60px; height: 60px; font-size: 1.5rem;">
                                                {{ strtoupper(substr($contact->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
            
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-0">
                                            <a href="{{ route('master.contact.show', $contact->id) }}" 
                                               class="text-decoration-none text-dark fw-semibold">
                                                {{ $contact->name }}
                                            </a>
                                        </h5>
                                        <p class="card-text mb-1 text-muted">{{ $contact->position ?? '-' }}</p>
            
                                        <p class="card-text small mb-0">
                                            <i class="fa fa-store me-1 text-info"></i> 
                                            {{ $contact->customer_name }} 
                                            ({{ $contact->customer_city }})
                                        </p>
            
                                        {{-- Badge sumber data --}}
                                        @if(isset($contact->source_label))
                                            <span class="badge bg-{{ $contact->source_label == 'Existing' ? 'success' : 'warning' }} mt-1">
                                                {{ $contact->source_label }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
            
                                <hr class="my-2">
            
                                <div class="row small">
                                    <div class="col-lg-6 col-sm-12 mb-2 mb-lg-0">
                                        <p class="mb-0">
                                            <i class="fa fa-phone me-1"></i> 
                                            Telp: {{ $contact->phone ?? '-' }}
                                        </p>
                                        <p class="mb-0">
                                            <i class="fa fa-birthday-cake me-1"></i> 
                                            DOB: {{ $contact->dob ? \Carbon\Carbon::parse($contact->dob)->format('d/m') : '-' }}
                                        </p>
                                    </div>
            
                                    <div class="col-lg-6 col-sm-12 text-end">
                                        <a class="btn btn-sm btn-info me-1" 
                                           href="{{ route('master.contact.show', $contact->id) }}" 
                                           title="Lihat">
                                            <i class="fa fa-eye"></i>
                                        </a>
            
                                        <a class="btn btn-sm btn-warning me-1" 
                                           href="{{ route('master.contact.edit', $contact->id) }}" 
                                           title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
            
                                        <form action="{{ route('master.contact.destroy', $contact->id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kontak ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            Tidak ada data kontak ditemukan.
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $contacts->appends(request()->input())->links() }}
            </div>
            
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.js-select2').select2({
            placeholder: "Pilih Toko",
            allowClear: true
        });

        // Hapus inisialisasi DataTables (sudah tidak ada tabel)
    });
</script>
@endsection