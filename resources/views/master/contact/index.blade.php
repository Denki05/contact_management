@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Notif -->
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

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item"><a href="#">Contact</a></li>
            <li class="breadcrumb-item active" aria-current="page">Index</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a href="{{ route('master.contact.create') }}" class="btn btn-success mb-3"><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#importExportModal">
                <i class="fa fa-file-excel"></i> Import/Export Excel
            </button>

            <!-- Modal -->
            <div class="modal fade" id="importExportModal" tabindex="-1" role="dialog" aria-labelledby="importExportModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importExportModalLabel">Manage Import & Export Contact</h5>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('contact.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="file">Import Excel File</label>
                                    <input type="file" name="file" class="form-control" required>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-success">Import</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <a href="{{ route('contact.exportTemplate') }}" class="btn btn-info">Download Template</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel -->
            <table class="table" id="contact_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Posisi</th>
                        <th>Store</th>
                        <th>Telepon</th>
                        <th>DOB</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    @if($contact->status == 1)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->position }}</td>
                        <td>{{ $contact->customer->name }} - {{ $contact->customer->text_kota }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ \Carbon\Carbon::parse($contact->dob)->format('d-m-Y')}}</td>
                        <td>
                            <a class="btn btn-info" href="{{ route('master.contact.show', $contact->id) }}" role="button">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            <form action="{{ route('master.contact.destroy', $contact->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $('#contact_table').DataTable({
            pageLength: 10
        });

        let modal = document.getElementById("importExportModal");
        if (modal) {
            console.log("Modal ditemukan dalam DOM!");
        } else {
            console.error("Modal TIDAK ditemukan dalam DOM!");
        }
    });
</script>
@endsection