@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Notifikasi -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Orders</a></li>
            <li class="breadcrumb-item"><a href="#">Existing</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <form method="POST" action="{{ route('orders.existing.update', $so->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Type Transaksi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="type_transaction" value="{{ $so->type_transaction }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Indent <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $so->so_indent ? 'YES' : 'NO' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Brand</label>
                                        <input type="text" class="form-control" name="brand_name" id="brand_name" value="{{ $so->brand_name }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Disc (%)</label>
                                        <input class="form-control" type="number" name="catatan" value="{{ $so->catatan }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Kontrak</label>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSoKontrak">
                                            Kontrak
                                        </button>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Note</label>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="fa fa-plus"></i> Note
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Produk yang sudah ada -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">#Edit Produk</h4>
                                <button type="button" class="btn btn-info row-add" data-id="0">
                                    <i class="fa fa-plus me-2"></i> Tambah
                                </button>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="orders_table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Produk</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Disc</th>
                                            <th class="text-center">Free</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($so_item as $product)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                <!-- <input type="text" style="text-align: center;" class="form-control" name="product_name[]" value="{{ $product->product_code }} {{ $product->product_name }}" readonly> -->
                                                <span class="name">{{ $product->product_code }} {{ $product->product_name }}</span>
                                                <input type="hidden" name="sku[]" value="{{ $product->product_id }}">
                                                <input type="hidden" name="so_kontrak_value[]" value="{{ $product->kontrak }}">
                                                <input type="hidden" name="packaging[]" value="{{ $product->packaging_id }}">
                                                <input type="hidden" name="deleted_items[]" class="deleted-items">
                                            </td>
                                            <td class="text-center">
                                                <input type="number" style="text-align: center;" class="form-control" name="price[]" value="{{ $product->price }}">
                                            </td>
                                            <td class="text-center">
                                                <input type="number" style="text-align: center;" class="form-control" name="qty[]" value="{{ $product->qty }}" step="any">
                                            </td>
                                            <td class="text-center">
                                                <input type="number" style="text-align: center;" class="form-control" name="disc[]" value="{{ $product->disc_usd }}">
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input free-checkbox" name="free[{{ $product->product_id }}]" value="1" {{ old('free.'.$product->product_id, $product->free_product) ? 'checked' : '' }}>
                                                <input type="hidden" class="free-hidden" name="free_hidden[{{ $product->product_id }}]" value="0">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm row-delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan dan Kembali -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <a href="{{ route('orders.existing.index') }}" class="btn btn-danger">Kembali</a>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addSoKontrak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Kontrak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <select class="form-select js-select2-kontrak" id="so_kontrak" name="so_kontrak" data-placeholder="Search" style="width: 100%;">
                </select>
            </div>

            <div class="modal-footer">
                
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <a href="#" class="row-add" data-id="1">
                    <button type="button" class="btn btn-info" id="addModalKontrak" data-dismiss="modal">
                        Add
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Note -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">#Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" name="note" id="editor" rows="4" cols="10"></textarea>
                <br>
                <a class="btn btn-info" id="test" href="javascript:void(0);" title="">Click</a>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('.js-select2').select2();

    $('#addSoKontrak').on('shown.bs.modal', function () {
        $(".js-select2-kontrak").select2({
            dropdownParent: $("#addSoKontrak"),
            ajax: {
                url: '{{ route('orders.existing.search_kontrak', ['id' => $so->customer_other_address_id, 'merek' => $so->brand_name]) }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        _token: "{{ csrf_token() }}"
                    };
                },
                cache: true,
            },
        });
    });


    $('.js-select2-kontrak').on('select2:select', function () {
        $.ajax({
            url: '{{ route('orders.existing.get_product_kontrak') }}',
            type: 'POST',
            data: {
                so_kontrak: $(this).val(),
                _token: "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function (json) {
                if (json.code === 200) {
                    product_kontrak = json.data;
                }
            },
            error: function () {
                alert("Terjadi kesalahan saat mengambil data produk.");
            }
        });
    });

    var product_data = new Object();
    var product_kontrak = new Object();

    $.ajax({
      url: '{{ route('orders.existing.get_product_pack') }}',
        data: {id:$('#brand_name').val() , _token: "{{csrf_token()}}"},
        type: 'POST',
        cache: false,
        dataType: 'json',
        success: function(json) {
          if (json.code == 200) {
            product_data = json.data;

            $.each( product_data, function( key, value ) {
                var makeselect;
                $.map( product_data, function( val, i ) {
                  if(val['typeName'] === null){
                    makeselect += '<option value="'+ val['id'] +'" data-name="'+ val['name'] +'" data-packname="'+ val['packName'] +'" data-price="'+ val['price'] +'" data-packid="'+ val['packID']+'">'+ val['code'] + ' - ' + val['name'] + ' - ' + val['packName'] + ' - '+ val['warehouseName'] +'</option>';
                  } else {
                    makeselect += '<option value="'+ val['id'] +'" data-name="'+ val['name'] +'" data-packname="'+ val['packName'] +'" data-price="'+ val['price'] +'" data-packid="'+ val['packID']+'">'+ val['code'] + ' - ' + val['name'] + ' - ' + val['packName'] + ' - '+ val['typeName'] +'</option>';
                  }
                });


                $('.js-ajax').append(makeselect);
                initailizeSelect2();
            });
          }
        }
    });

    let counter = 1;

    var table = $('#orders_table').DataTable({
        paging: false,
        bInfo : false,
        searching: false,
        columns: [
          {name: 'counter', "visible": false},
          {name: 'sku', orderable: false, width: "40%"},
          {name: 'price', orderable: false, searcable: false, width: "10%"},
          {name: 'qty', orderable: false, searcable: false, width: "10%"},
          {name: 'disc', orderable: false, searcable: false, width: "10%"},
          {name: 'free', orderable: false, searcable: false, width: "5%"},
          {name: 'action', orderable: false, searcable: false, width: "5%"}
        ],
        'order' : [[0,'desc']]
    })
    
    $(document).on('click', '.row-add', function (e) {
        e.preventDefault();
        var typeAdd = $(this).data('id');
        
        if (!product_data || Object.keys(product_data).length === 0) {
            alert('Data produk belum dimuat, silakan tunggu beberapa saat.');
            return;
        }

        if($('#brand_name').val()) {
            if(typeAdd == 0){
            
                makeselect = '<select class="js-select2 form-control js-ajax" id="sku['+counter+']" name="sku[]" data-placeholder="Select Product" style="width:100%;" required><option></option>';
                
                $.map( product_data, function( val, i ) {
                    if(val['typeName'] === null){
                    makeselect += '<option value="'+ val['id'] +'" data-name="'+ val['name'] +'" data-packname="'+ val['packName'] +'" data-price="'+ val['price'] +'" data-packid="'+ val['packID']+'">'+ val['code'] + ' - ' + val['name'] + ' - ' + val['packName'] + ' - '+ val['warehouseName'] +'</option>';
                    } else {
                    makeselect += '<option value="'+ val['id'] +'" data-name="'+ val['name'] +'" data-packname="'+ val['packName'] +'" data-price="'+ val['price'] +'" data-packid="'+ val['packID']+'">'+ val['code'] + ' - ' + val['name'] + ' - ' + val['packName'] + ' - '+ val['typeName'] +'</option>';
                    }
                    
                });

                makeselect += '</select>';

                table.row.add([
                            counter,
                            makeselect,
                            '<input type="number" class="form-control" name="price[]" style="text-align: center;"><input type="hidden" class="form-control packaging" name="packaging[]">',
                            '<input type="number" class="form-control" name="qty[]" style="text-align: center;" required step="0.001">',
                            '<input type="number" class="form-control" name="disc[]" style="text-align: center;">',
                            '<input type="checkbox" class="form-check-input input-gift" id="gift" name="gift"><input class="form-control input-free" type="hidden" id="free_product" value="0" name="free_product[]">',
                            '<a href="#" class="row-delete"><button type="button" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button></a>'
                            ]).draw( false );
                            
                            initailizeSelect2();
                counter++;
            }else{
                makeselect = '<select class="js-select2 form-control js-ajax-kontrak" id="sku['+counter+']" name="sku[]" data-placeholder="Select Product" style="width:100%" required><option></option>';

                $.map( product_kontrak, function( val, i ) {
                    makeselect += '<option value="'+ val['product_id'] +'" data-kontrak="'+ val['kontrak_id'] +'" data-name="'+ val['product_name'] +'" data-code="'+ val['product_code'] +'" data-price="'+ val['product_price'] +'" data-packaging="'+ val['packaging_id'] +'" data-disc="'+ val['product_disc'] + '">'+ val['product_code'] + ' - ' + val['product_name'] + ' - ' + val['packaging_name']  +'</option>';
                });

                makeselect += '</select>';

                table.row.add([
                            counter,
                            '<input class="form-check-input" type="checkbox" value="1" name="check_kontrak" id="check_kontrak" disabled checked><input type="hidden" class="form-control" value="1" name="value_kontrak[]"><input type="hidden" class="form-control" name="kontrak_so_id[]">',
                            makeselect,
                            '<input type="number" class="form-control" name="price[]" style="text-align: center;" readonly><input type="hidden" class="form-control packaging" name="packaging[]">',
                            '<input type="number" class="form-control noscroll" name="qty[]" style="text-align: center;" required>',
                            '<input type="number" class="form-control noscroll usd_disc" style="text-align: center;" name="disc[]">',
                            '<input type="checkbox" class="form-check-input input-gift" id="gift" name="gift" disabled><input class="form-control input-free" type="hidden" id="free_product" value="0" name="free_product[]">',
                            '<a href="#" class="row-delete"><button type="button" class="btn btn-sm btn-circle btn-alt-danger" title="Delete"><i class="fa fa-trash"></i></button></a>'
                        ]).draw( false );
                        
                        initailizeSelectKontrak2();
                counter++;
            }
        }
    });

    function initailizeSelect2(){
      $(".js-ajax").select2();

      $('.js-ajax').on('select2:select', function (e) {
        var price = $(this).find(':selected').data('price');
        $(this).parents('tr').find('input[name="price[]"]').val(price);

        var pack = $(this).find(':selected').data('packid');
        $(this).parents('tr').find('input[name="packaging[]"]').val(pack);
      });
    };

    function initailizeSelectKontrak2(){
      $(".js-ajax-kontrak").select2();

      $('.js-ajax-kontrak').on('select2:select', function (e) {
        var kontrak = $(this).find(':selected').data('kontrak');
        $(this).parents('tr').find('input[name="kontrak_so_id[]"]').val(kontrak);

        var price = $(this).find(':selected').data('price');
        $(this).parents('tr').find('input[name="price[]"]').val(price);

        var disc = $(this).find(':selected').data('disc');
        $(this).parents('tr').find('.usd_disc').val(disc);

        var pack = $(this).find(':selected').data('packaging');
        $(this).parents('tr').find('input[name="packaging[]"]').val(pack);
      });
    }
    
    $('#orders_table tbody').on( 'click', '.row-delete', function (e) {
      e.preventDefault();
      table.row( $(this).parents('tr') ).remove().draw();

      if(typeof $('input[name="id[]"]').val() == 'undefined') {
        $('#submit-table').prop('disabled', true);
      }
    });

    $(".row-delete").click(function () {
            let row = $(this).closest("tr"); // Ambil baris tabel tempat tombol diklik
            let productId = row.find('input[name^="sku"]').val(); // Ambil SKU produk
            
            if (productId) {
                $("<input>").attr({
                    type: "hidden",
                    name: "deleted_items[]",
                    value: productId
                }).appendTo("form"); // Tambahkan input hidden ke form
            }

            row.remove(); // Hapus baris dari tampilan
    });

    $(".row-delete").click(function () {
        let row = $(this).closest("tr");
        let productId = row.find('input[name^="sku"]').val(); // Ambil SKU produk
        if (productId) {
            $("<input>").attr({
                type: "hidden",
                name: "deleted_items[]",
                value: productId
            }).appendTo("form");
        }
        row.remove();
    });

    $("#test").on("click", function (e) {
        e.preventDefault();
        addListItem();
    });

    function addListItem() {
        var text = document.getElementById('editor').value;
        var listNumberRegex = /^[0-9]+(?=\.)/gm;
        var existingNums = [];
        var match;

        // Temukan angka terakhir dalam daftar yang ada
        while ((match = listNumberRegex.exec(text)) !== null) {
            existingNums.push(parseInt(match[0], 10));
        }

        // Urutkan angka yang ditemukan
        existingNums.sort((a, b) => a - b);

        var addListItemNum = existingNums.length > 0 ? existingNums[existingNums.length - 1] + 1 : 1;
        var exp = '\n' + addListItemNum + '.\xa0';

        document.getElementById('editor').value += exp;
    }

    $(document).on('change', '.free-checkbox', function () {
        let hiddenInput = $(this).closest('td').find('.free-hidden');
        hiddenInput.val($(this).is(':checked') ? 1 : 0);
    });


});

</script>
@endsection