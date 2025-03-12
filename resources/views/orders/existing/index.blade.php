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
            <li class="breadcrumb-item"><a href="#">Orders</a></li>
            <li class="breadcrumb-item"><a href="#">Existing</a></li>
            <li class="breadcrumb-item active" aria-current="page">Index</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fa fa-plus mr-10"></i> Create
            </button>
        </div>

        <br>
        <br>

        {{--<form method="GET" action="{{ route('master.contact.index') }}" class="mb-3">
          <div class="mb-3">
            <label for="store_id" class="form-label">Pilih Toko</label>
            <select name="store_id" id="store_id" class="form-select">
              <option value="">Pilih Customer</option>
              @foreach($customers as $row)
              <option value="{{ $row->id }}">{{ $row->name }}  {{ $row->text_kota }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fa fa-search"></i> Cari
          </button>
          <a href="{{ route('master.contact.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa fa-refresh"></i> Reset
          </a>
        </form>--}}

        <div class="table-responsive">
            <table class="table table-striped" id="orders_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Nota</th>
                        <th>Brand</th>
                        <th>Customer</th>
                        <th>Sales</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($so as $index => $row)
                      <tr>
                          <td>{{ $index + 1 }}</td>
                          <td>{{ $row->so_code }}</td>
                          <td>{{ $row->code }}</td>
                          <td>{{ $row->nota_brand }}</td>
                          <td>{{ $row->customer_name }} {{ $row->customer_kota }}</td>
                          <td>{{ $row->sales }}</td>
                          <td>{{ $row->so_created_by }}</td>
                          <td>{{ $row->so_created_at }}</td>
                          <td>{{ $row->status_so }}</td>
                          <td>
                            @if($row->status_so == "AWAL")
                            <a class="btn btn-warning btn-sm" href="{{ route('orders.existing.edit', $row->id) }}" role="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <a class="btn btn-success btn-sm" href="#" role="button"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                            @endif
                          </td>
                      </tr>
                  @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal add so -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">#Add SO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          @csrf
          <div class="row">
            <div class="col">
              <div class="mb-3">
                <label class="form-label"><b>Customer</b> <span class="text-danger">*</span></label>
                <select class="form-select js-select2" id="account_member" name="member_name" style="width: 100%;">
                  <option value="">Pilih Customer</option>
                  @foreach($customers as $row)
                  <option value="{{$row->id}}">{{$row->name}} {{$row->text_kota}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col">
              <div class="mb-3">
                <label class="form-label"><b>Brand</b> <span class="text-danger">*</span></label>
                <select class="form-select js-select2" id="merek_ppi" name="brand_name" style="width: 100%;">
                  <option value="">Pilih Brand</option>
                  @foreach($brand as $row)
                  <option value="{{$row->brand_name}}">{{$row->brand_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="mb-3">
                <label class="form-label"><b>Type Transaksi</b> <span class="text-danger">*</span></label>
                <select class="form-select js-select2" name="so_type" id="so_type" style="width: 100%;">
                  <option value="">Pilih Transaksi Type</option>
                  <option value="1">CASH</option>
                  <option value="2">TEMPO</option>
                  <option value="3">MARKETPLACE</option>
                  <option value="4">COD</option>
                </select>
              </div>
            </div>
            <div class="col">
              <div class="mb-3">
                <label class="form-label"><b>Indent</b> <span class="text-danger">*</span></label>
                <select class="form-select js-select2" name="so_indent" id="indent_so" style="width: 100%;">
                  <option value="">Pilih status indent</option>
                  <option value="0">NO</option>
                  <option value="1">YES</option>
                </select>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="button" id="addSO" class="btn btn-primary">Add</button>
      </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#orders_table').DataTable({
            order: [
                [1, 'desc'],
            ],
            pageLength: 10,
            lengthMenu: [
                [10, 20, 50],
                [10, 20, 50]
            ],
        });

        $('.js-select2').select2({
            dropdownParent: $('#exampleModal') // Pastikan Select2 muncul di dalam modal
        });

        $('#addSO').on('click', function() {
            var customer = $('#account_member').val();
            var merek = $('#merek_ppi').val();
            var type_so = $('#so_type').val();
            var indent_so = $('#indent_so').val();
            var step_so = 1;

            var url = '{{ route("orders.existing.create", ["step" => ":step", "brand" => ":brand", "customer" => ":customer", "type" => ":type", "indent" => ":indent"]) }}';
            url = url.replace(':customer', encodeURIComponent(customer)); 
            url = url.replace(':brand', encodeURIComponent(merek)); 
            url = url.replace(':type', encodeURIComponent(type_so));
            url = url.replace(':indent', encodeURIComponent(indent_so));
            url = url.replace(':step', encodeURIComponent(step_so));


            $.ajax({
                url: url,
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data)
                {
                window.location.href = url;
                }
            });
        });
    });
</script>
@endsection