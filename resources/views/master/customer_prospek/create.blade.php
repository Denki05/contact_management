@extends('layouts.app')

@section('content')
<div class="container-fluid px-2">
    <div id="alert-block"></div>

    <div class="card shadow-lg">
        <div class="card-body">
            <form action="{{ route('master.customer_prospek.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="ajax">
                @csrf

                <div class="mb-4">
                </div>

                <fieldset>
                    <h5 class="mb-3 fw-bold">Data Profile</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="name" class="col-sm-4 col-form-label">Nama <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Name Store" value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="owner_name" class="col-sm-4 col-form-label">Owner <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" id="owner_name" name="owner_name" class="form-control" placeholder="Owner Store" value="{{ old('owner_name') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Telepon</label>
                                <div class="col-sm-8 d-flex gap-2">
                                    <input type="text" class="form-control" name="phone1" placeholder="Telephone 1" value="{{ old('phone1') }}">
                                    <input type="text" class="form-control" name="phone2" placeholder="Telephone 2" value="{{ old('phone2') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="email" class="col-sm-4 col-form-label">Email</label>
                                <div class="col-sm-8">
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="website" class="col-sm-4 col-form-label">Web / Sosmed</label>
                                <div class="col-sm-8">
                                    <input type="text" id="website" name="website" class="form-control" placeholder="Website Store" value="{{ old('website') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="image_store" class="col-sm-4 col-form-label">Image Store</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="image_store" name="image_store" type="file">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="website" class="col-sm-4 col-form-label">PIC</label>
                                <div class="col-sm-8">
                                    <input type="text" id="pic" name="pic" class="form-control" placeholder="PIC" value="{{ old('pic') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="website" class="col-sm-4 col-form-label">Officer</label>
                                <div class="col-sm-8">
                                    <input type="text" id="officer" name="officer" class="form-control" placeholder="Officer" value="{{ old('officer') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="category_id" class="col-sm-4 col-form-label">Kategori</label>
                                <div class="col-sm-8">
                                    <select id="category_id" name="category_id" class="form-control js-select2" style="width: 100%;">
                                        <option value="">Pilih kategori</option>
                                        @foreach ($kategori as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="text_provinsi">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="category_id" class="col-sm-4 col-form-label">Pengajuan</label>
                                <div class="col-sm-8">
                                    <select name="pengajuan" id="pengajuan" class="form-control">
                                        <option value="" selected>Pilih pengajuan</option>
                                        @foreach ($pengajuanList as $key => $value)
                                            <option value="{{ $key }}" {{ old('pengajuan') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('master.customer_prospek.index') }}" class="btn btn-warning text-white"><i class="fa fa-arrow-left"></i> Back</a>
                        <button type="button" class="btn btn-primary btn-next">Next <i class="fa fa-arrow-right"></i></button>
                    </div>
                </fieldset>

                <fieldset class="d-none">
                    <h5 class="mb-3 fw-bold">Data Geo Tag</h5>
                    <div class="mb-3 row">
                        <label for="address" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea id="address" name="address" rows="3" class="form-control" placeholder="Alamat Store">{{ old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="province" class="col-sm-4 col-form-label">Provinsi</label>
                                <div class="col-sm-8">
                                    <select id="province" name="province" class="form-control js-select2" style="width: 100%;">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->prov_id }}">{{ $province->prov_name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="text_provinsi">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="city" class="col-sm-4 col-form-label">Kota</label>
                                <div class="col-sm-8">
                                    <select id="city" name="city" class="form-control js-select2" style="width: 100%;">
                                        <option value="">Pilih Kota</option>
                                    </select>
                                    <input type="hidden" name="text_kota">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label for="zone" class="col-sm-4 col-form-label">Zone</label>
                                <div class="col-sm-8">
                                    <select id="zone" name="zone" class="form-control js-select2" style="width: 100%;">
                                        <option value="">Pilih Zone</option>
                                        <option value="JABODETABEK">ZONA 1 : JABODETABEK</option>
                                        <option value="JABAR">ZONA 2 : JABAR</option>
                                        <option value="JATENG - JATIM">ZONA 3 : JATENG - JATIM</option>
                                        <option value="SUMATERA">ZONA 4 : SUMATERA</option>
                                        <option value="BALI - KALIMANTAN - SULAWESI">ZONA 5 : BALI - KALIMANTAN - SULAWESI</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary btn-prev"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="submit" class="btn btn-success">Submit <i class="fa fa-check"></i></button>
                    </div>
                </fieldset>

                <!--<fieldset class="d-none">-->
                <!--    <h5 class="mb-3 fw-bold">Data Finance</h5>-->
                <!--    <div class="row">-->
                <!--        <div class="col-md-6">-->
                <!--            <div class="mb-3 row">-->
                <!--                <label for="ktp" class="col-sm-4 col-form-label">KTP</label>-->
                <!--                <div class="col-sm-8">-->
                <!--                    <input type="text" id="ktp" name="ktp" class="form-control" placeholder="KTP" value="{{ old('ktp') }}">-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--        <div class="col-md-6">-->
                <!--            <div class="mb-3 row">-->
                <!--                <label for="limit_credit" class="col-sm-4 col-form-label">NPWP</label>-->
                <!--                <div class="col-sm-8">-->
                <!--                    <input type="text" id="npwp" name="npwp" class="form-control" placeholder="NPWP" value="{{ old('npwp') }}">-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->

                <!--    <div class="d-flex justify-content-between mt-4">-->
                <!--        <button type="button" class="btn btn-secondary btn-prev"><i class="fa fa-arrow-left"></i> Back</button>-->
                <!--        <button type="submit" class="btn btn-success">Submit <i class="fa fa-check"></i></button>-->
                <!--    </div>-->
                <!--</fieldset>-->
            </form>
        </div>
    </div>
</div>

{{-- Wizard Script --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const steps = document.querySelectorAll("fieldset");
        let current = 0;

        function showStep(index) {
            steps.forEach((s, i) => s.classList.toggle("d-none", i !== index));
        }

        document.querySelectorAll(".btn-next").forEach(btn => {
            btn.addEventListener("click", () => {
                if (current < steps.length - 1) {
                    current++;
                    showStep(current);
                }
            });
        });

        document.querySelectorAll(".btn-prev").forEach(btn => {
            btn.addEventListener("click", () => {
                if (current > 0) {
                    current--;
                    showStep(current);
                }
            });
        });

        showStep(current);
    });
    
    $(document).ready(function() {
        $('.js-select2').select2({});
        
        // Listener untuk dropdown Provinsi
        $('#province').on('change', function() {
            let prov_id = $(this).val();
        
            // Mengambil nama (teks) dari provinsi yang dipilih
            let province_name = $('#province option:selected').text();
        
            // Mengisi input hidden 'text_provinsi'
            $('input[name="text_provinsi"]').val(province_name);
        
            // Mengosongkan dropdown Kota dan input hidden kota saat provinsi berubah
            $('#city').html('<option value="">Pilih Kota</option>');
            $('input[name="text_kota"]').val('');
            
            // Jika ada ID provinsi, lakukan panggilan Ajax
            if (prov_id) {
                $.ajax({
                    url: '{{ route('master.customer_prospek.getkabupaten') }}',
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        prov_id: prov_id
                    },
                    success: function(data) {
                        $('#city').html(data);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });
        
        // Listener untuk dropdown Kota
        $('#city').on('change', function() {
            // Mengambil nama (teks) dari kota yang dipilih
            let city_name = $('#city option:selected').text();
        
            // Mengisi input hidden 'text_kota'
            $('input[name="text_kota"]').val(city_name);
        });
    });
</script>
@endsection