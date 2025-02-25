@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item"><a href="{{ route('master.contact.index') }}">Contact</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form method="POST" action="{{ route('master.contact.update', $contact->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col">
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $contact->name) }}" required>
                    </div>
                    <div class="col">
                        <label for="dob" class="form-label">DOB</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $contact->dob) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="manage_id" class="form-label">Customer <span class="text-danger">*</span></label>
                        <select class="form-select js-select2" id="manage_id" name="manage_id" required>
                            <option value="">Pilih Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (old('manage_id', $contact->manage_id) == $customer->id) ? 'selected' : '' }}>
                                    {{ $customer->name }} {{ $customer->text_kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label for="posisi" class="form-label">Posisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $contact->position) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="phone" name="phone" value="{{ old('phone', $contact->phone) }}" required>
                    </div>
                    <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $contact->email) }}">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="ktp" class="form-label">KTP</label>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control" id="ktp" name="ktp" placeholder="Input No KTP">
                            <input class="form-control" type="file" id="image_ktp" name="image_ktp">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="npwp" class="form-label">NPWP</label>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control" id="npwp" name="npwp" placeholder="Input No NPWP">
                            <input class="form-control" type="file" id="image_npwp" name="image_npwp">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('master.contact.index') }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        Update <i class="fa fa-arrow-right ms-2"></i>
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
        $('.js-select2').select2({ tags: true });
    });
</script>
@endsection