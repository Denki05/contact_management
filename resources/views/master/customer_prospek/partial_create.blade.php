<style>
/* ── Progress bar ── */
.wiz-wrap { padding: 8px 4px; }
.wiz-progress {
    display: flex; align-items: flex-start;
    margin-bottom: 20px; padding: 0 8px;
}
.wiz-step {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; gap: 5px; position: relative;
}
.wiz-step:not(:last-child)::after {
    content: ''; position: absolute;
    top: 13px; left: calc(50% + 18px);
    width: calc(100% - 36px); height: 2px;
    background: rgba(255,255,255,0.08); z-index: 0; transition: background .3s;
}
.wiz-step.ws-done:not(:last-child)::after { background: #0c82f9; }
.wiz-dot {
    width: 28px; height: 28px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.12);
    background: #2a2f35; color: #6b7280;
    font-size: 12px; font-weight: 700; z-index: 1;
    display: flex; align-items: center; justify-content: center;
    transition: all .2s;
}
.wiz-step.ws-active .wiz-dot { border-color: #0c82f9; background: #0c2a4a; color: #60a5fa; }
.wiz-step.ws-done   .wiz-dot { border-color: #0c82f9; background: #0c82f9; color: #fff; }
.wiz-lbl { font-size: 10px; color: #6b7280; text-align: center; white-space: nowrap; }
.wiz-step.ws-active .wiz-lbl { color: #60a5fa; font-weight: 700; }
.wiz-step.ws-done   .wiz-lbl { color: #0c82f9; }

/* ── Panels ── */
.wiz-panel { display: none; }
.wiz-panel.wp-show { display: block; }

/* ── Section title ── */
.wiz-sec-title {
    font-size: 11px; font-weight: 700; color: #6b7280;
    text-transform: uppercase; letter-spacing: .6px;
    margin-bottom: 12px; padding-bottom: 6px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}

/* ── Form fields ── */
.wf-grid   { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.wf-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
.wf-field  { display: flex; flex-direction: column; gap: 4px; }
.wf-field.span2 { grid-column: span 2; }
.wf-field label {
    font-size: 10px; color: #6b7280;
    text-transform: uppercase; letter-spacing: .4px;
}
.wf-field label .req { color: #f87171; }
.wf-field input[type=text],
.wf-field input[type=email],
.wf-field input[type=tel],
.wf-field input[type=file],
.wf-field select,
.wf-field textarea {
    background: #2a2f35; border: 1px solid rgba(255,255,255,0.1);
    border-radius: 6px; color: #e5e7eb; padding: 6px 10px; font-size: 12px;
    transition: border-color .12s;
}
.wf-field input:focus, .wf-field select:focus, .wf-field textarea:focus {
    outline: none; border-color: #0c82f9;
}
.wf-field input::placeholder,
.wf-field textarea::placeholder { color: #4b5563; }
.wf-hint { font-size: 10px; color: #4b5563; margin-top: 2px; }
.wf-phone { display: flex; gap: 6px; }
.wf-phone input { flex: 1; }

/* ── Info box ── */
.wiz-info {
    background: #0c2a4a; border: 1px solid #1d4ed8; border-radius: 6px;
    padding: 8px 12px; font-size: 11px; color: #93c5fd;
    margin-bottom: 12px; display: flex; align-items: flex-start; gap: 6px;
}
.wiz-info.wi-warning {
    background: #451a03; border-color: #92400e; color: #fcd34d;
}

/* ── Select2 override dark ── */
.select2-container--default .select2-selection--single {
    background-color: #2a2f35 !important; color: #e5e7eb !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    height: 34px !important; padding: 4px 8px; border-radius: 6px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #e5e7eb !important; line-height: 26px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 32px !important;
}
.select2-dropdown { background: #2a2f35 !important; border: 1px solid rgba(255,255,255,0.1) !important; }
.select2-container--default .select2-results__option { color: #e5e7eb !important; background: #2a2f35 !important; }
.select2-container--default .select2-results__option--highlighted { background: #0c82f9 !important; color: #fff !important; }
.select2-container { width: 100% !important; }

/* ── Footer buttons ── */
.wiz-footer {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 16px; padding-top: 12px;
    border-top: 1px solid rgba(255,255,255,0.07);
}
.btn-wf-cancel {
    background: transparent; border: none; color: #6b7280;
    font-size: 12px; cursor: pointer; padding: 4px 8px;
}
.btn-wf-cancel:hover { color: #e5e7eb; }
.btn-wf-prev {
    background: transparent; border: 1px solid rgba(255,255,255,0.12);
    border-radius: 6px; color: #9ca3af; padding: 5px 14px;
    font-size: 12px; cursor: pointer;
    display: inline-flex; align-items: center; gap: 4px;
}
.btn-wf-prev:hover { background: rgba(255,255,255,0.07); color: #e5e7eb; }
.btn-wf-next {
    background: #0c82f9; border: none; border-radius: 6px; color: #fff;
    padding: 5px 16px; font-size: 12px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 4px;
}
.btn-wf-next:hover { background: #0970d8; }
.btn-wf-save {
    background: #059669; border: none; border-radius: 6px; color: #fff;
    padding: 5px 16px; font-size: 12px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 4px;
}
.btn-wf-save:hover { background: #047857; }

@media (max-width: 576px) {
    .wf-grid   { grid-template-columns: 1fr; }
    .wf-grid-3 { grid-template-columns: 1fr 1fr; }
    .wf-field.span2 { grid-column: span 1; }
}
</style>

<div class="wiz-wrap">
    <div style="font-size:13px; font-weight:700; color:#e5e7eb; margin-bottom:14px;">
        Tambah Customer Prospek
    </div>

    {{-- Progress bar --}}
    <div class="wiz-progress" id="wizProgress">
        <div class="wiz-step ws-active" data-step="0">
            <div class="wiz-dot">1</div>
            <div class="wiz-lbl">Data Profile</div>
        </div>
        <div class="wiz-step" data-step="1">
            <div class="wiz-dot">2</div>
            <div class="wiz-lbl">Geo Tag</div>
        </div>
        <div class="wiz-step" data-step="2">
            <div class="wiz-dot">3</div>
            <div class="wiz-lbl">Atribut</div>
        </div>
    </div>

    <form id="createCustomerForm" method="POST"
        action="{{ route('master.customer_prospek.store') }}"
        enctype="multipart/form-data">
        @csrf

        {{-- ══ STEP 1: DATA PROFILE ══ --}}
        <div class="wiz-panel wp-show" id="wizStep0">
            <div class="wiz-sec-title">Data Profile</div>
            <div class="wf-grid">
                <div class="wf-field">
                    <label>Nama Store <span class="req">*</span></label>
                    <input type="text" name="name" placeholder="Nama toko / perusahaan"
                        value="{{ old('name') }}" required>
                </div>
                <div class="wf-field">
                    <label>Owner / Contact Person <span class="req">*</span></label>
                    <input type="text" name="owner_name" placeholder="Nama pemilik"
                        value="{{ old('owner_name') }}" required>
                </div>
                <div class="wf-field">
                    <label>No. HP</label>
                    <div class="wf-phone">
                        <input type="tel" name="phone1" placeholder="HP Utama"
                            value="{{ old('phone1') }}">
                        <input type="tel" name="phone2" placeholder="HP Kedua"
                            value="{{ old('phone2') }}">
                    </div>
                    <span class="wf-hint">HP opsional, bisa dilengkapi AO nanti</span>
                </div>
                <div class="wf-field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@toko.com"
                        value="{{ old('email') }}">
                </div>
                <div class="wf-field">
                    <label>Web / Sosmed</label>
                    <input type="text" name="website" placeholder="https:// atau @username"
                        value="{{ old('website') }}">
                </div>
                <div class="wf-field">
                    <label>Image Store</label>
                    <input type="file" name="image_store" accept="image/*">
                    <span class="wf-hint">Opsional, maks 2MB (jpg/png)</span>
                </div>
            </div>
            <div class="wiz-footer">
                <button type="button" class="btn-wf-cancel" id="cancelCreate">
                    ✕ Batal
                </button>
                <button type="button" class="btn-wf-next" onclick="wizNext(0)">
                    Lanjut &rsaquo;
                </button>
            </div>
        </div>

        {{-- ══ STEP 2: GEO TAG ══ --}}
        <div class="wiz-panel" id="wizStep1">
            <div class="wiz-info">
                <i class="bi bi-info-circle-fill"></i>
                Data Geo Tag bersifat opsional dan dapat dilengkapi setelah mutasi.
            </div>
            <div class="wiz-sec-title">Data Geo Tag</div>
            <div class="wf-grid">
                <div class="wf-field span2">
                    <label>Alamat</label>
                    <textarea name="address" rows="2"
                        placeholder="Alamat lengkap toko">{{ old('address') }}</textarea>
                </div>
                <div class="wf-field">
                    <label>Provinsi</label>
                    <select id="province" name="province" class="js-select2">
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinces as $prov)
                        <option value="{{ $prov->prov_id }}"
                            {{ old('province') == $prov->prov_id ? 'selected' : '' }}>
                            {{ $prov->prov_name }}
                        </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="text_provinsi" id="text_provinsi">
                </div>
                <div class="wf-field">
                    <label>Kota / Kabupaten</label>
                    <select id="city" name="city" class="js-select2">
                        <option value="">Pilih Kota</option>
                    </select>
                    <input type="hidden" name="text_kota" id="text_kota">
                </div>
                <div class="wf-field">
                    <label>Zone</label>
                    <select name="zone" class="js-select2">
                        <option value="">Pilih Zone</option>
                        <option value="JABODETABEK"  {{ old('zone')=='JABODETABEK'?'selected':'' }}>ZONA 1 : JABODETABEK</option>
                        <option value="JABAR"         {{ old('zone')=='JABAR'?'selected':'' }}>ZONA 2 : JABAR</option>
                        <option value="JATENG - JATIM"{{ old('zone')=='JATENG - JATIM'?'selected':'' }}>ZONA 3 : JATENG - JATIM</option>
                        <option value="SUMATERA"      {{ old('zone')=='SUMATERA'?'selected':'' }}>ZONA 4 : SUMATERA</option>
                        <option value="BALI - KALIMANTAN - SULAWESI" {{ old('zone')=='BALI - KALIMANTAN - SULAWESI'?'selected':'' }}>ZONA 5 : BALI - KAL - SUL</option>
                    </select>
                </div>
                <div class="wf-field">
                    <label>Sumber Data</label>
                    <select name="sumber">
                        <option value="event">Event / Guestbook</option>
                        <option value="apm">APM</option>
                        <option value="online">Online (Form)</option>
                    </select>
                </div>
            </div>
            <div class="wiz-footer">
                <button type="button" class="btn-wf-prev" onclick="wizGoto(0)">
                    &lsaquo; Kembali
                </button>
                <button type="button" class="btn-wf-next" onclick="wizNext(1)">
                    Lanjut &rsaquo;
                </button>
            </div>
        </div>

        {{-- ══ STEP 3: ATRIBUT ══ --}}
        <div class="wiz-panel" id="wizStep2">
            <div class="wiz-info wi-warning">
                <i class="bi bi-shield-exclamation"></i>
                PIC &amp; Officer akan dikunci sampai <strong>status_request = Disetujui</strong> oleh SPV.
                Kategori &amp; Pengajuan wajib diisi sebelum bisa diajukan ke SPV.
            </div>
            <div class="wiz-sec-title">Atribut &amp; Observasi Bisnis</div>
            <div class="wf-grid">
                <div class="wf-field">
                    <label>Kategori <span class="req">*</span></label>
                    <select name="category_id" class="js-select2" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $k)
                        <option value="{{ $k->id }}"
                            {{ old('category_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="wf-field">
                    <label>Pengajuan <span class="req">*</span></label>
                    <select name="pengajuan" class="js-select2" required>
                        <option value="">Pilih Pengajuan</option>
                        @foreach($pengajuanList as $key => $val)
                        <option value="{{ $key }}"
                            {{ old('pengajuan') == $key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="wf-field">
                    <label>PIC <span class="wf-hint" style="font-size:10px;">(diset SPV saat approve)</span></label>
                    <input type="text" name="pic" placeholder="Kosongkan, diset SPV"
                        value="{{ old('pic') }}">
                </div>
                <div class="wf-field">
                    <label>Officer <span class="wf-hint" style="font-size:10px;">(diset SPV saat approve)</span></label>
                    <input type="text" name="officer" placeholder="Kosongkan, diset SPV"
                        value="{{ old('officer') }}">
                </div>
            </div>
            <div class="wiz-footer">
                <button type="button" class="btn-wf-prev" onclick="wizGoto(1)">
                    &lsaquo; Kembali
                </button>
                <button type="submit" class="btn-wf-save">
                    <i class="bi bi-check-lg"></i> Simpan Prospek
                </button>
            </div>
        </div>

    </form>
</div>

<script>
$(document).ready(function(){

    // ── Init Select2 dengan dark theme ──
    function initSelect2(context){
        $(context || '#dynamicContainer').find('.js-select2').select2({
            width: '100%', dropdownAutoWidth: true,
            dropdownParent: $('#dynamicContainer')
        });
    }
    initSelect2();

    // ── Province → City cascade ──
    $(document).off('change','#province').on('change','#province', function(){
        let provId = $(this).val();
        $('#text_provinsi').val($('#province option:selected').text());
        if(!provId){
            $('#city').html('<option value="">Pilih Kota</option>'); return;
        }
        $.ajax({
            url  : "{{ route('master.customer_prospek.getkabupaten') }}",
            type : 'POST',
            data : { prov_id: provId, _token: $('meta[name="csrf-token"]').attr('content') },
            beforeSend(){ $('#city').html('<option>Loading...</option>'); },
            success(res){
                let html = '<option value="">Pilih Kota</option>';
                res.forEach(r => html += `<option value="${r.id}">${r.name}</option>`);
                $('#city').html(html);
                if($('#city').hasClass('select2-hidden-accessible')){
                    $('#city').select2('destroy').select2({
                        width:'100%', dropdownParent:$('#dynamicContainer')
                    });
                }
            }
        });
    });

    $(document).off('change','#city').on('change','#city', function(){
        $('#text_kota').val($('#city option:selected').text());
    });

    // ── Submit form ──
    $(document).off('submit','#createCustomerForm').on('submit','#createCustomerForm', function(e){
        e.preventDefault();
        let fd = new FormData(this);
        Swal.fire({title:'Menyimpan...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});
        $.ajax({
            url: $(this).attr('action'), type:'POST',
            data: fd, processData: false, contentType: false,
            success(res){
                if(res.notification && res.notification.type === 'alert-danger'){
                    Swal.fire('Peringatan', res.notification.content, 'warning');
                } else {
                    Swal.fire('Berhasil','Data prospek berhasil disimpan.','success')
                        .then(()=> window.loadCustomerList('prospek', 1)); // Tambahkan window.
                }
            },
            error(xhr){
                Swal.close();
                if(xhr.status === 422){
                    let msg = Object.values(xhr.responseJSON.errors).map(v=>v.join('<br>')).join('<br>');
                    Swal.fire('Validasi Gagal', msg, 'error');
                } else if(xhr.status === 400 && xhr.responseJSON?.notification){
                    Swal.fire('Peringatan', xhr.responseJSON.notification.content, 'warning');
                } else {
                    Swal.fire('Gagal','Terjadi kesalahan pada server.','error');
                }
            }
        });
    });

    // Cancel button
    $(document).off('click','#cancelCreate').on('click','#cancelCreate', function(){
        window.loadCustomerList(window.currentCustomerType, window.currentCustomerPage); // Tambahkan window.
    });
});

// ── Wizard navigation ──
function wizGoto(step){
    $('.wiz-panel').removeClass('wp-show');
    $('#wizStep' + step).addClass('wp-show');
    $('.wiz-step').each(function(i){
        $(this).removeClass('ws-active ws-done');
        if(i < step)  $(this).addClass('ws-done');
        if(i === step) $(this).addClass('ws-active');
    });
    // Re-init select2 di step baru
    $('#wizStep' + step + ' .js-select2').select2({
        width:'100%', dropdownParent:$('#dynamicContainer')
    });
}

window.wizNext = function(current){
    // Validasi step 0
    if(current === 0){
        let name  = $('[name=name]').val().trim();
        let owner = $('[name=owner_name]').val().trim();
        if(!name || !owner){
            Swal.fire({
                title:'Data belum lengkap',
                text:'Nama Store dan Owner wajib diisi.',
                icon:'warning', background:'#1e2227', color:'#e5e7eb'
            }); return;
        }
    }
    wizGoto(current + 1);
};
</script>