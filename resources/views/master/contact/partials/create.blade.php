<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="alert alert-info">
                Membuat Contact untuk: 
                <strong>{{ $selected_customer->name ?? '-' }} ({{ $selected_customer->text_kota ?? '-' }})</strong>
            </div>

            <form id="createContactForm" method="POST" action="{{ route('master.contact.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="manage_id" value="{{ $encoded_id }}">

                {{-- Row 1: Nama & DOB --}}
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror"
                            placeholder="Nama Kontak" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">DOB <span class="text-danger">*</span></label>
                        <input type="date" name="dob" class="form-control form-control-sm @error('dob') is-invalid @enderror"
                            value="{{ old('dob') }}" required>
                        @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Row 2: Posisi & Telepon --}}
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label">Posisi <span class="text-danger">*</span></label>
                        <input type="text" name="posisi" class="form-control form-control-sm @error('posisi') is-invalid @enderror"
                            placeholder="Posisi Kontak" value="{{ old('posisi') }}" required>
                        @error('posisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                            placeholder="Nomor Telepon" value="{{ old('phone') }}" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Row 3: Email & Address --}}
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                            placeholder="Email (opsional)" value="{{ old('email') }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control form-control-sm @error('address') is-invalid @enderror"
                            placeholder="Alamat (opsional)" value="{{ old('address') }}">
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Row 4: KTP & NPWP --}}
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label">KTP</label>
                        <input type="text" name="ktp" class="form-control form-control-sm mb-1 @error('ktp') is-invalid @enderror"
                            placeholder="No KTP" value="{{ old('ktp') }}">
                        
                        @error('ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @error('image_ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NPWP</label>
                        <input type="text" name="npwp" class="form-control form-control-sm mb-1 @error('npwp') is-invalid @enderror"
                            placeholder="No NPWP" value="{{ old('npwp') }}">
                        
                        @error('npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @error('image_npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Submit & Cancel --}}
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="cancelCreateContact" class="btn btn-danger">
                        <i class="fa fa-arrow-left me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        Submit <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
