@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table" id="customer_table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Telepon 1</th>
                        <th scope="col">Telepon 2</th>
                        <th scope="col">Kota</th>
                        <th scope="col">Provinsi</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($customers as $customer)
                    @php
                        $phones = explode(',', $customer->phone);
                    @endphp
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->address }}</td>
                        <td>{{ trim($phones[0] ?? '-') }}</td>
                        <td>{{ trim($phones[1] ?? '-') }}</td>
                        <td>{{ $customer->text_kota }}</td>
                        <td>{{ $customer->text_provinsi }}</td>
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
        $('#customer_table').DataTable({
            pageLength: 10 // Atur jumlah data per halaman
        });
    });
</script>
@endsection