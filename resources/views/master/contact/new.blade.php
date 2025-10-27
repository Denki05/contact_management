@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">New Contact - Pilih Customer/Store</div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form method="GET" id="selectCustomerForm">
                        <div class="mb-3">
                            <label for="encoded_id" class="form-label">Customer / Store <span class="text-danger">*</span></label>
                            <select class="form-select js-select2" id="encoded_id" name="encoded_id" required>
                                <option value="">Pilih Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->encoded_id }}">
                                        [{{ ucfirst($customer->source) }}] - {{ $customer->name }} {{ $customer->text_kota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="btnContinue">
                                Lanjut <i class="fa fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.js-select2').select2({
            placeholder: "Pilih Customer",
            allowClear: true
        });
        
        $('#btnContinue').on('click', function(e) {
            e.preventDefault();
            const encodedId = $('#encoded_id').val(); // gunakan encoded_id, bukan manage_id
            if (encodedId) {
                // Redirect ke route create dengan encoded_id (misal: 191.1)
                window.location.href = "{{ url('contact/create') }}/" + encodedId;
            } else {
                alert('Pilih Customer terlebih dahulu.');
            }
        });
    });
</script>
@endsection