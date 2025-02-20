@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            @if (session()->has('welcome_message'))
                <div id="welcome-card" class="card shadow-lg border-0 rounded-4">
                    <div class="card-body text-center p-4">
                        <h5 class="mb-3">Selamat datang, <strong>{{ Auth::user()->name }}</strong>! 👋</h5>
                        <p class="text-muted">Anda berhasil login ke sistem.</p>
                    </div>
                </div>

                <script>
                    // Sembunyikan card setelah 10 detik
                    setTimeout(() => {
                        let welcomeCard = document.getElementById('welcome-card');
                        if (welcomeCard) {
                            welcomeCard.style.transition = "opacity 1s";
                            welcomeCard.style.opacity = "0";
                            setTimeout(() => welcomeCard.style.display = "none", 1000);
                        }
                    }, 10000); // 10 detik (bisa disesuaikan)
                </script>
            @endif
        </div>
    </div>
</div>
@endsection