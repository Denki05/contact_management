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
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $contact->name) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">DOB <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $contact->dob) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Customer <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <select class="form-control js-select2" id="manage_id" name="manage_id">
                                    <option value="">Pilih Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('manage_id', $contact->manage_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} {{ $customer->text_kota }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Posisi <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $contact->position) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Telepon <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $contact->phone) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $contact->email) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label for="ktp" class="col-sm-2 col-form-label">KTP</label>
                            <div class="col-sm-10">
                                <div class="form-row">
                                    <div class="col">
                                        <input type="text" class="form-control" id="ktp" name="ktp" value="{{ old('ktp', $contact->ktp) }}">
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="file" id="image_ktp" name="image_ktp">
                                        @if($contact->image_ktp)
                                            <img src="{{ asset('superuser_assets/media/master/contact/' . $contact->image_ktp) }}" alt="KTP" class="img-thumbnail mt-2" width="150">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                        <label for="ktp" class="col-sm-2 col-form-label">NPWP</label>
                            <div class="col-sm-10">
                                <div class="form-row">
                                    <div class="col">
                                        <input type="text" class="form-control" id="npwp" name="npwp" value="{{ old('npwp', $contact->npwp) }}">
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="file" id="image_npwp" name="image_npwp">
                                        @if($contact->image_npwp)
                                            <img src="{{ asset('superuser_assets/media/master/contact/' . $contact->image_npwp) }}" alt="NPWP" class="img-thumbnail mt-2" width="150">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row pt-30">
                    <div class="col-md-6">
                        <a href="{{ route('master.contact.index') }}" class="btn btn-danger text-white">
                            <i class="fa fa-arrow-left mr-10"></i> Back
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-success text-white">
                            Update <i class="fa fa-arrow-right ml-10"></i>
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
        $('.js-select2').select2();
    });
</script>
@endsection
