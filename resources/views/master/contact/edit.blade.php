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

                <div class="row">
                    <div class="col-md-6">
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $contact->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>DOB <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="dob" value="{{ old('dob', $contact->dob) }}" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Customer <span class="text-danger">*</span></label>
                        <select class="form-control js-select2" name="manage_id" required>
                            <option value="">Pilih Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('manage_id', $contact->manage_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->text_kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Posisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="position" value="{{ old('position', $contact->position) }}" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>KTP</label>
                        <input type="text" class="form-control" name="ktp" value="{{ old('ktp', $contact->ktp) }}" required>
                        <input class="form-control mt-2" type="file" name="image_ktp" id="image_ktp">
                        @if($contact->image_ktp)
                            <img id="ktpPreview" src="{{ asset('storage/superuser_assets/media/master/contact/' . $contact->image_ktp) }}" class="img-thumbnail mt-2" width="150">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label>NPWP</label>
                        <input type="text" class="form-control" name="npwp" value="{{ old('npwp', $contact->npwp) }}" required>
                        <input class="form-control mt-2" type="file" name="image_npwp" id="image_npwp">
                        @if($contact->image_npwp)
                            <img id="npwpPreview" src="{{ asset('storage/superuser_assets/media/master/contact/' . $contact->image_npwp) }}" class="img-thumbnail mt-2" width="150">
                        @endif
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <a href="{{ route('master.contact.index') }}" class="btn btn-danger text-white">Back</a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-success text-white">Update</button>
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
        $('.js-select2').select2();
    });
</script>
@endsection