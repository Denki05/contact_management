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
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="name">Nama <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Input Nama">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="dob">DOB <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="dob" name="dob" placeholder="Input DOB">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="manage_id">Customer <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <select class="form-control js-select2" id="manage_id" name="manage_id">
                                    <option value="">Pilih Customer</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->text_kota }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="posisi">Posisi <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="posisi" name="posisi" placeholder="Input Posisi">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="phone">Telepon <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="phone" name="phone" placeholder="Input Telepon">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="posisi">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Input Email (optional)">
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
                                        <input type="text" class="form-control" id="ktp" name="ktp" placeholder="Input No KTP">
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="file" id="image_ktp" name="image_ktp">
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
                                        <input type="text" class="form-control" id="npwp" name="npwp" placeholder="Input No NPWP">
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="file" id="image_npwp" name="image_npwp">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row pt-30">
                    <div class="col-md-6">
                        <a href="{{ route('master.contact.index') }}">
                            <button type="button" class="btn btn-danger text-white">
                                <i class="fa fa-arrow-left mr-10"></i> Back
                            </button>
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-success text-white">
                            Submit <i class="fa fa-arrow-right ml-10"></i>
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