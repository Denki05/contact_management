@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="alert alert-info" role="alert">
                Membuat Contact untuk: 
                <strong>{{ $selected_customer->name ?? 'Tidak Diketahui' }}
                {{ $selected_customer->text_kota ?? '-' }}</strong>
            </div>

            <form method="POST" action="{{ route('master.contact.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="manage_id" value="{{ $encoded_id }}">
                
                <div class="row mb-3">
                    <div class="col">
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" placeholder="Input Nama" value="{{ old('name') }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col">
                        <label for="dob" class="form-label">DOB <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('dob') is-invalid @enderror" 
                            id="dob" name="dob" value="{{ old('dob') }}">
                        @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="posisi" class="form-label">Posisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('posisi') is-invalid @enderror" 
                            id="posisi" name="posisi" placeholder="Input Posisi" value="{{ old('posisi') }}">
                        @error('posisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col">
                        <label for="phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('phone') is-invalid @enderror" 
                            id="phone" name="phone" placeholder="Input Telepon" value="{{ old('phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" placeholder="Input Email (optional)" value="{{ old('email') }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" 
                            id="address" name="address" placeholder="Input Alamat Berbeda" value="{{ old('address') }}">
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="ktp" class="form-label">KTP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-2 @error('ktp') is-invalid @enderror" 
                            id="ktp" name="ktp" placeholder="Input No KTP" value="{{ old('ktp') }}">
                        <input class="form-control @error('image_ktp') is-invalid @enderror" type="file" id="image_ktp" name="image_ktp">
                        @error('ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @error('image_ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col">
                        <label for="npwp" class="form-label">NPWP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-2 @error('npwp') is-invalid @enderror" 
                            id="npwp" name="npwp" placeholder="Input No NPWP" value="{{ old('npwp') }}">
                        <input class="form-control @error('image_npwp') is-invalid @enderror" type="file" id="image_npwp" name="image_npwp">
                        @error('npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @error('image_npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('master.contact.new') }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        Submit <i class="fa fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    // Tidak ada inisialisasi khusus, tapi siap jika perlu validasi dinamis nanti
});
</script>
@endsection