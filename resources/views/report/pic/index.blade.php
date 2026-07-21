@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('report.pic.generate') }}" target="_blank">
                @csrf
                <div class="d-flex flex-wrap align-items-end gap-2 small">

                    {{-- Tanggal Mulai --}}
                    <div>
                        <label for="date_end" class="form-label mb-0">Tanggal Awal</label>
                        <input type="date" name="date_start" id="date_start"
                               value="{{ request('date_start', now()->startOfMonth()->format('Y-m-d')) }}"
                               class="form-control form-control-sm">
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div>
                        <label for="date_end" class="form-label mb-0">Tanggal Selesai</label>
                        <input type="date" name="date_end" id="date_end"
                               value="{{ request('date_end', now()->format('Y-m-d')) }}"
                               class="form-control form-control-sm">
                    </div>

                    {{-- PIC --}}
                    <div>
                        <label for="date_end" class="form-label mb-0">PIC</label>
                        <select name="pic_id" id="pic_id" class="form-select form-select-sm js-select2">
                            <option value="">Pilih PIC</option>
                            @foreach($customers->unique('pic') as $cust)
                                <option value="{{ $cust->pic }}">
                                    {{ $cust->pic }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Customer --}}
                    <div>
                        <label for="date_end" class="form-label mb-0">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select form-select-sm js-select2">
                            <option value="">Pilih Customer</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" {{ request('customer_id') == $cust->id ? 'selected' : '' }}>
                                    {{ $cust->name }} {{ $cust->text_kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Product --}}
                    <div>
                        <label for="date_end" class="form-label mb-0">Product</label>
                        <select name="product_id" id="product_id" class="form-select form-select-sm js-select2">
                            <<option value="">Pilih Product</option>
                            @foreach($products as $row)
                                <option value="{{ $row->id }}" {{ request('product_id') == $row->id ? 'selected' : '' }}>
                                    {{ $row->code }} - {{ $row->name }} / {{ $row->packaging->pack_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Type Report --}}
                    <div>
                        <label for="date_end" class="form-label mb-0">Type Report</label>
                        <select name="type_report" id="type_report" class="form-select form-select-sm">
                            <option value="">Pilih Type</option>
                            <option value="v1">PIC V1</option>
                          <option value="v2">PIC V2</option>
                          <option value="v3">PIC V3</option>
                        </select>
                    </div>

                    {{-- Tombol Export --}}
                    <div class="flex-shrink-0">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-file-pdf"></i> Export PDF
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet/dist/plugins/css/pluginsCss.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet/dist/plugins/plugins.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet/dist/css/luckysheet.css" />
@endpush

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/luckysheet/dist/plugins/js/plugin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luckysheet/dist/luckysheet.umd.js"></script>

<script>
    $(document).ready(function () {
        $('.js-select2').select2({
            width: '100%',
            allowClear: true
        });
    });
</script>
@endsection
