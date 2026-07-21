<style>
    /* ===================== RAPATKAN FORM ===================== */
    .mb-3 { margin-bottom: 0.5rem !important; }
    .p-3 { padding: 1rem !important; }

    /* ===================== FIELDSET WIZARD ===================== */
    fieldset { display: none; margin: 0; padding: 0; }
    fieldset.active { display: block; }

    /* ===================== SELECT2 STYLING ===================== */
    .select2-container--default .select2-selection--single {
        background-color: #fff !important;
        color: #000 !important;
        border: 1px solid #ced4da !important;
        height: 38px !important;
        padding: 4px 6px;
        border-radius: 0.25rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #000 !important;
        line-height: 28px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        top: 2px;
    }

    .select2-container--default .select2-results__option {
        color: #000 !important;
        background-color: #fff !important;
    }

    .select2-container--default .select2-results__option--selected {
        background-color: #e9ecef !important;
        color: #000 !important;
    }

    .select2-container { width: 100% !important; }
</style>

<div class="modal fade" id="selectCustomerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title">Pilih Customer</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="customerSelect" class="form-label text-white fw-semibold">Customer</label>
        <select id="customerSelect" class="form-select form-select-lg js-select2" style="width: 100%;">
            <option value="" style="color:black;">-- Pilih Customer --</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" style="color:black;">{{ $customer->name }} ({{ $customer->text_kota ?? '-' }})</option>
            @endforeach
        </select>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="btnSelectCustomer" class="btn btn-primary">Lanjut</button>
      </div>
    </div>
  </div>
</div>


@push('scripts')
<script>
$(document).ready(function(){
    $('.js-select2').select2({
        dropdownParent: $('#selectCustomerModal'),
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
@endpush