<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikasi Profesi - LSP USK</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background-color: #cfcccc;
        }

        .btn-amber {
            background-color: #f59e0b;
            color: #0f172a;
            font-weight: 700;
            border: none;
        }

        .btn-amber:hover {
            background-color: #d97706;
            color: #ffffff;
        }

        .logo-glow {
            filter: drop-shadow(0 0 25px rgba(245, 158, 11, 0.35));
        }

        .text-navy {
            color: #0f2c57 !important;
        }
    </style>
</head>

<body class="text-light min-vh-100 d-flex flex-column justify-content-between p-3 p-md-4">

    <!-- Header / Navbar Minimalis -->
    <header class="container-fluid max-w-6xl py-2">
        <div class="d-flex justify-content-between items-center align-items-center">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('images/logo-lsp-removebg-preview.png') }}" alt="Logo LSP" style="height: 40px;">
                <span class="fw-bold fs-5 tracking-wide text-navy">LSP</span>
            </div>
            <div>
                <a href="/LSP/login" class="btn btn-secondary fw-semibold text-secondary-subtle fs-7">
                    Login/Regist &rarr;
                </a>
            </div>
        </div>
    </header>

    <!-- Hero / Main Section -->
    <main class="container text-center my-auto py-5" style="max-width: 720px;">
        <div class="mb-4">
            <img src="{{ asset('images/logo-lsp-removebg-preview.png') }}" alt="Logo LSP" class="img-fluid logo-glow"
                style="max-height: 180px;">
        </div>

        <h1 class="display-5 fw-bold text-black mb-3">
            Sistem Sertifikasi Profesi
        </h1>
        <p class="text-secondary fs-5 mb-4 leading-relaxed">
            Selamat datang di Portal Resmi Uji Kompetensi LSP USK. Silakan mendaftar untuk mengikuti uji kompetensi
            skema pilihan Anda.
        </p>

        <!-- Tombol Aksi -->
        {{-- <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center w-100">
            <a href="{{ route('register.index') }}" 
               class="btn btn-amber px-4 py-3 rounded-3 shadow">
                Form Pendaftaran Peserta
            </a>
            <a href="/admin/login" 
               class="btn btn-outline-secondary text-light px-4 py-3 rounded-3">
                Portal Admin & Asesor
            </a>
        </div> --}}
    </main>

    <!-- Footer -->
    <footer class="container text-center py-3 fs-7 text-secondary border-top border-dark">
        &copy; {{ date('Y') }} LSP USK - Junior Web Developer. All rights reserved.
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
