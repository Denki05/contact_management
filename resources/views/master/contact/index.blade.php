@extends('layouts.app')

@section('content')

<div class="container">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                    @if($contact->status == 1)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->position }}</td>
                        <td>{{ $contact->address?? '-' }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ \Carbon\Carbon::parse($contact->dob)->format('d-m-Y')}}</td>
                        <td>
                            <a class="btn btn-warning" href="{{ route('master.contact.edit', $contact->id) }}" role="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>
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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGaHf0j2zF6i5z0p5aVOF9p5a" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy1FWRpQp6iPb4SxXof6EG3niJk6od7+8a7T1w" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#contact_table').DataTable({
            pageLength: 10 // Atur jumlah data per halaman
        });
    });
</script>
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