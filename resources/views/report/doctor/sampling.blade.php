@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <h4 class="mb-4">List Sampling</h4>

    @if(session('error') || isset($error))
        <div class="alert alert-danger">{{ session('error') ?? $error }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Customer</th>
                            <th style="width: 15%;">PIC</th>
                            <th style="width: 15%;">Tanggal</th>
                            <th>Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['customer_name'] }}</td>
                            <td>
                                <span class="badge bg-primary">{{ strtoupper($item['pic']) }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('d-m-Y H:i') }}</td>
                            <td>
                                <div class="accordion" id="produkAccordion{{ $index }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $index }}">
                                            <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                                                {{ count($item['detail']) }} Produk
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#produkAccordion{{ $index }}">
                                            <div class="accordion-body p-2">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($item['detail'] as $prod)
                                                        <li class="mb-1">
                                                            <strong>{{ $prod['product_name'] }}</strong> - {{ $prod['product_code'] }}
                                                            <span class="text-muted">({{ $prod['brand_name'] }}, {{ $prod['kemasan'] }})</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection