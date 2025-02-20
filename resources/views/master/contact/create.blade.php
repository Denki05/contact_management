@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item"><a href="{{ route('master.contact.index') }}">Contact</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <form method="POST" action="{{ route('master.contact.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Input Nama">
                    </div>
                    <div class="col-md-6">
                        <label for="dob" class="form-label">DOB <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="dob" name="dob">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="manage_id" class="form-label">Customer <span class="text-danger">*</span></label>
                        <select class="form-control js-select2" id="manage_id" name="manage_id">
                            <option value="">Pilih Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->text_kota }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="posisi" class="form-label">Posisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="posisi" name="posisi" placeholder="Input Posisi">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="phone" name="phone" placeholder="Input Telepon">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Input Email (optional)">
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

                <div class="row mt-4">
                    <div class="col-md-6">
                        <a href="{{ route('master.contact.index') }}" class="btn btn-danger text-white">
                            <i class="fa fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-success text-white">
                            Submit <i class="fa fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.js-select2').select2({
            tags: true
        });
    });
</script>
@endsection