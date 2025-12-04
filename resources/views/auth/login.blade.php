@extends('layouts.app')

@section('content')
<style>
    /* Latar belakang gelap lembut yang menenangkan */
    .aesthetic-background {
        background-color: #f0f2f5; /* Warna dasar abu-abu sangat terang */
        background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%); /* Gradasi biru muda sangat lembut */
        min-height: 100vh;
    }

    /* Kartu form dengan efek Neumorphism/Soft Shadow */
    .aesthetic-card {
        border: none;
        border-radius: 1.5rem; /* Sudut lebih membulat */
        box-shadow: 20px 20px 60px rgba(0, 0, 0, 0.1), /* Shadow gelap di bawah */
                    -20px -20px 60px rgba(255, 255, 255, 0.8); /* Shadow terang di atas */
        backdrop-filter: blur(10px); /* Efek blur ringan */
        background: rgba(255, 255, 255, 0.95); /* Sedikit transparan */
    }

    /* Warna Primary yang lebih modern (misalnya, Teal/Indigo yang elegan) */
    .btn-primary {
        background-color: #10b981 !important; /* Warna Teal Hijau */
        border-color: #059669 !important;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #059669 !important;
        border-color: #047857 !important;
        transform: translateY(-2px); /* Efek angkat ringan saat hover */
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.4);
    }
    
    /* Input group dan form control yang bersih */
    .input-group-text, .form-control {
        border-radius: 0.75rem !important; /* Sudut membulat pada input */
        border-color: #e0eafc !important;
    }

    .form-control:focus {
        border-color: #10b981 !important; 
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25) !important;
    }
</style>

<div class="aesthetic-background d-flex align-items-center justify-content-center min-vh-100 p-3">
    <div class="row w-100 justify-content-center">
        <div class="col-md-7 col-lg-5 col-xl-4">
            <div class="card aesthetic-card">
                
                <div class="card-body p-4 p-md-5">
                    
                    <!-- <div class="text-center mb-5">
                        <i class="fas fa-lock text-primary mb-3" style="font-size: 2.5rem;"></i>
                        <h2 class="fw-bold text-dark">Selamat Datang Kembali</h2>
                        <p class="text-muted mb-0">Silakan masukkan detail akun Anda</p>
                    </div> -->

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <!-- <label for="username" class="form-label fw-semibold text-secondary">Nama Pengguna</label> -->
                            <div class="input-group input-group-lg">
                                <!-- <span class="input-group-text bg-white border-end-0"><i class="fas fa-user text-muted"></i></span> -->
                                <input type="text" id="username" 
                                       class="form-control @error('username') is-invalid @enderror border-start-0" 
                                       name="username" value="{{ old('username') }}" 
                                       required autocomplete="username" autofocus 
                                       placeholder="Username Anda">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <!-- <label for="password" class="form-label fw-semibold text-secondary">Kata Sandi</label> -->
                            <div class="input-group input-group-lg">
                                <!-- <span class="input-group-text bg-white border-end-0"><i class="fas fa-key text-muted"></i></span> -->
                                <input type="password" id="password" 
                                       class="form-control @error('password') is-invalid @enderror border-start-0" 
                                       name="password" required autocomplete="current-password" 
                                       placeholder="Kata sandi Anda">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> MASUK
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection