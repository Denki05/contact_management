{{-- ============================= --}}
{{-- Partial index kontak (AJAX) --}}
{{-- ============================= --}}

<div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="text-white m-0">Daftar Kontak</h6>

    <div class="d-flex gap-3">
        <form method="GET" action="{{ route('master.contact.partialIndex') }}" class="d-flex gap-1" id="contactSearchForm">
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Cari nama contact atau customer"
                   value="{{ $search ?? '' }}" style="width: 230px;">
            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
        </form>

        <button id="loadCreateContactForm" class="btn btn-success btn-sm">
            <i class="fa fa-plus me-1"></i> Tambah Contact
        </button>
    </div>
</div>

<style>
.contact-item {
    background-color: #3a3f47;
    padding: 4px 8px;
    border-radius: 4px;
    margin-bottom: 3px;
}
</style>

@php $currentCustomer = null; @endphp

<div class="contact-body">
@foreach ($rows as $contact)
    @if ($currentCustomer !== $contact->customer_name)
        @if ($currentCustomer !== null)
                </div>
            </div>
        @endif
        @php $currentCustomer = $contact->customer_name; @endphp
        <div class="card mb-2 border-0" style="background-color:#1f2329;">
            <div class="card-header bg-dark text-white fw-bold py-1 px-2" style="font-size: 14px;">
                <i class="fa fa-store me-1"></i> {{ $currentCustomer }}
            </div>
            <div class="card-body py-1 px-2" style="background-color:#2a2f36;">
    @endif

    <div class="contact-item text-white small d-flex align-items-center flex-wrap"
         style="gap: 6px; overflow:hidden;">
        <span class="fw-bold">{{ $contact->name }}</span>
        <span>|</span>
        <span>{{ $contact->position ?: '-' }}</span>
        <span>|</span>
        <span><i class="fa fa-phone me-1"></i>{{ str_replace(['-', ' ', '.', '/'], '', $contact->phone ?: '-') }}</span>
        <span>|</span>
        <span>
            DOB :
            {{ $contact->dob 
                ? \Carbon\Carbon::parse($contact->dob)
                    ->locale('id')
                    ->translatedFormat('d F')
                : '-' 
            }}
        </span>


    </div>

@endforeach
@if ($currentCustomer !== null)
        </div>
    </div>
@endif
</div> {{-- END .contact-body --}}

{{-- PAGINATION --}}
<div class="mt-2 text-center contact-pagination">
    <div class="d-inline-flex align-items-center gap-1">
        @if ($rows->onFirstPage())
            <span class="btn btn-sm btn-secondary disabled">Prev</span>
        @else
            <button class="btn btn-sm btn-primary ajax-pagination" data-page="{{ $rows->currentPage() - 1 }}">Prev</button>
        @endif

        @php
            $start = max(1, $rows->currentPage() - 2);
            $end   = min($rows->lastPage(), $rows->currentPage() + 2);
        @endphp

        @if ($start > 1)
            <button class="btn btn-sm btn-outline-light ajax-pagination" data-page="1">1</button>
            @if ($start > 2)
                <span class="text-white">...</span>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $rows->currentPage())
                <span class="btn btn-sm btn-warning fw-bold">{{ $i }}</span>
            @else
                <button class="btn btn-sm btn-outline-light ajax-pagination" data-page="{{ $i }}">{{ $i }}</button>
            @endif
        @endfor

        @if ($end < $rows->lastPage())
            @if ($end < $rows->lastPage() - 1)
                <span class="text-white">...</span>
            @endif
            <button class="btn btn-sm btn-outline-light ajax-pagination" data-page="{{ $rows->lastPage() }}">{{ $rows->lastPage() }}</button>
        @endif

        @if ($rows->hasMorePages())
            <button class="btn btn-sm btn-primary ajax-pagination" data-page="{{ $rows->currentPage() + 1 }}">Next</button>
        @else
            <span class="btn btn-sm btn-secondary disabled">Next</span>
        @endif
    </div>
</div>
