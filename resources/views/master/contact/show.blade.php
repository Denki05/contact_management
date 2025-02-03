@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Master</a></li>
        <li class="breadcrumb-item"><a href="{{ route('master.contact.index') }}">Contact</a></li>
        <li class="breadcrumb-item active" aria-current="page">Show</li>
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
                                <input type="text" class="form-control" id="name" name="name" value="{{ $contact->name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="dob">DOB <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="dob" name="dob" value="{{ $contact->dob }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="manage_id">Customer <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="manage_id" name="manage_id" value="{{ $contact->customer->name }} - {{ $contact->customer->text_kota }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="posisi">Posisi <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="posisi" name="posisi" value="{{ $contact->position}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="phone">Telepon <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="posisi" name="posisi" value="{{ $contact->phone}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="posisi">Email</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="posisi" name="posisi" value="{{ $contact->email ?? '-' }}" readonly>
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
                                        <input type="text" class="form-control" id="ktp" name="ktp" value="{{ $contact->ktp }}" readonly>
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
                                        <input type="text" class="form-control" id="npwp" name="npwp" value="{{ $contact->npwp ?? '-' }}" readonly>
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