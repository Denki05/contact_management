<div class="card shadow-lg border-0 bg-dark text-white p-3">
    <div class="alert alert-info">Pilih Customer/Store untuk membuat Contact</div>

    <form id="selectCustomerForm">
        <div class="mb-3">
            <label for="encoded_id" class="form-label">Customer / Store <span class="text-danger">*</span></label>
            <select class="form-select js-select2" id="encoded_id" name="encoded_id" required>
                <option value="">Pilih Customer</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->encoded_id }}">
                        [{{ ucfirst($customer->source) }}] - {{ $customer->name }} ({{ $customer->text_kota }})
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