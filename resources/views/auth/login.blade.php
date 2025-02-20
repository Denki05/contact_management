@extends('layouts.app')

@section('content')
<section class="background-radial-gradient overflow-hidden">
  <style>
    .background-radial-gradient {
      background-color: hsl(218, 41%, 15%);
      background-image: radial-gradient(650px circle at 0% 0%,
          hsl(218, 41%, 35%) 15%,
          hsl(218, 41%, 30%) 35%,
          hsl(218, 41%, 20%) 75%,
          hsl(218, 41%, 19%) 80%,
          transparent 100%),
        radial-gradient(1250px circle at 100% 100%,
          hsl(218, 41%, 45%) 15%,
          hsl(218, 41%, 30%) 35%,
          hsl(218, 41%, 20%) 75%,
          hsl(218, 41%, 19%) 80%,
          transparent 100%);
    }

    #radius-shape-1, #radius-shape-2 {
      position: absolute;
      background: radial-gradient(#44006b, #ad1fff);
    }

    #radius-shape-1 {
      height: 220px;
      width: 220px;
      top: -150px;
      left: -130px;
      border-radius: 50%;
    }

    #radius-shape-2 {
      border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
      bottom: -50px;
      right: -90px;
      width: 280px;
      height: 280px;
    }

    .bg-glass {
      background-color: rgba(255, 255, 255, 0.85) !important;
      backdrop-filter: saturate(200%) blur(20px);
    }

    .btn-primary:hover {
      background-color: #6610f2;
      border-color: #520dc2;
    }

    .social-btn {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }
  </style>

  <div class="container px-4 py-5 text-center text-lg-start">
    <div class="row gx-lg-5 align-items-center">
      <div class="col-lg-6">
        <h2 class="my-5 display-5 fw-bold text-white">
          A satisfied customer is<br />
          <span class="text-primary">The best business strategy of all</span>
        </h2>
      </div>

      <div class="col-lg-6 position-relative">
        <div id="radius-shape-1" class="shadow-lg"></div>
        <div id="radius-shape-2" class="shadow-lg"></div>

        <div class="card bg-glass shadow-lg rounded-4">
          <div class="card-body p-5">
            <form method="POST" action="{{ route('login') }}">
              @csrf

              <!-- Username input -->
              <div class="mb-4">
                <label for="username" class="form-label fw-semibold">Username</label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="fa fa-user"></i></span>
                  <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" 
                         name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                </div>
                @error('username')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <!-- Password input -->
              <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="fa fa-lock"></i></span>
                  <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                         name="password" required autocomplete="current-password">
                </div>
                @error('password')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <!-- Remember Me & Forgot Password -->
              {{--<div class="d-flex justify-content-between mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember" name="remember">
                  <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">Lupa Password?</a>
              </div>--}}

              <!-- Submit button -->
              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg rounded-3">
                  <i class="fa fa-sign-in-alt"></i> Masuk
                </button>
              </div>

              <!-- Social login -->
              {{--<div class="text-center mt-4">
                <p class="fw-light">Atau masuk dengan:</p>
                <div class="d-flex justify-content-center gap-2">
                  <a href="#" class="btn btn-outline-dark social-btn"><i class="fab fa-facebook-f"></i></a>
                  <a href="#" class="btn btn-outline-dark social-btn"><i class="fab fa-google"></i></a>
                  <a href="#" class="btn btn-outline-dark social-btn"><i class="fab fa-twitter"></i></a>
                  <a href="#" class="btn btn-outline-dark social-btn"><i class="fab fa-github"></i></a>
                </div>
              </div>

              <!-- Register link -->
              <div class="text-center mt-3">
                <p>Belum punya akun? <a href="{{ route('register') }}" class="text-primary">Daftar Sekarang</a></p>
              </div>--}}

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection