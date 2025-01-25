@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Master</a></li>
        <li class="breadcrumb-item"><a href="#">Contact</a></li>
        <li class="breadcrumb-item active" aria-current="page">Index</li>
    </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Tombol Create -->
            <a href="{{ route('master.contact.create') }}" class="btn btn-success mb-3"><i class="fa fa-plus" aria-hidden="true"></i> Create</a>

            <!-- Tabel -->
            <table class="table" id="contact_table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Posisi</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Telepon</th>
                        <th scope="col">DOB</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->position }}</td>
                        <td>{{ $contact->address?? '-' }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ \Carbon\Carbon::parse($contact->dob)->format('d-m-Y')}}</td>
                        <td>
                            <a class="btn btn-warning" href="#" role="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <a class="btn btn-danger" href="#" role="button"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#contact_table').DataTable({
            pageLength: 10 // Atur jumlah data per halaman
        });
    });
</script>
@endsection