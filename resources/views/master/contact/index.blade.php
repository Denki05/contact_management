@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Notifikasi -->
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
            <a href="{{ route('master.contact.create') }}" class="btn btn-success mb-3">
                <i class="fa fa-plus"></i> Create
            </a>
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#importExportModal">
                <i class="fa fa-file-excel"></i> Import/Export Excel
            </button>

            <!-- Modal -->
            <div class="modal fade" id="importExportModal" tabindex="-1" aria-labelledby="importExportModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importExportModalLabel">Manage Import & Export Contact</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('contact.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="file" class="form-label">Import Excel File</label>
                                    <input type="file" name="file" class="form-control" required>
                                </div>
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

            <!-- Search Form -->
            <form method="GET" action="{{ route('master.contact.index') }}" class="mb-3">
                <div class="mb-3">
                    <label for="store_id" class="form-label">Pilih Toko</label>
                    <select name="store_id" id="store_id" class="form-select js-select2">
                        <option value="">-- Pilih Toko --</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }} - {{ $store->text_kota }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-search"></i> Cari
                </button>
                <a href="{{ route('master.contact.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-refresh"></i> Reset
                </a>
            </form>

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-striped" id="contact_table">
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
                            <td>{{ $contact->dob ? \Carbon\Carbon::parse($contact->dob)->format('d-m') : '-' }}</td>
                            <td>
                                <a class="btn btn-info btn-sm" href="{{ route('master.contact.show', $contact->id) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a class="btn btn-warning btn-sm" href="{{ route('master.contact.edit', $contact->id) }}">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <form action="{{ route('master.contact.destroy', $contact->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fa fa-trash"></i>
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
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.js-select2').select2({
            placeholder: "Pilih Toko",
            allowClear: true
        });

        $('#contact_table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[1, 'asc']], // Default sorting berdasarkan kolom pertama
            columnDefs: [
                { orderable: false, targets: [6] } // Disable sorting di kolom "Action"
            ]
        });
    });
</script>
@endsection