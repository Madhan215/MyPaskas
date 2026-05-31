<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - MyPaskas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Nunito', sans-serif;
        }

        body {
            height: 100vh;
            overflow: hidden;
            background: linear-gradient(135deg, #14532d 0%, #166534 50%, #15803d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 36px 32px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-icon {
            font-size: 4rem;
            text-align: center;
            margin-bottom: 8px;
        }

        h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #14532d;
            text-align: center;
        }

        .subtitle {
            color: #6b7280;
            text-align: center;
            margin-bottom: 28px;
        }

        .form-control {
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 1.05rem;
            border: 2px solid #d1fae5;
        }

        .form-control:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.2);
        }

        .form-label {
            font-weight: 700;
            color: #374151;
            font-size: 1rem;
        }

        .btn-login {
            background: #16a34a;
            border: none;
            color: #fff;
            border-radius: 12px;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 800;
            width: 100%;
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            background: #15803d;
            color: #fff;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 2px solid #d1fae5;
            border-right: none;
            background: #f0fdf4;
            color: #16a34a;
            font-size: 1.2rem;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }

        .role-hint {
            background: #f0fdf4;
            border-radius: 12px;
            padding: 14px;
            font-size: 0.9rem;
            color: #374151;
            margin-top: 20px;
        }

        .role-hint h6 {
            color: #14532d;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .role-hint code {
            background: #dcfce7;
            padding: 2px 6px;
            border-radius: 4px;
            color: #15803d;
        }
    </style>
</head>

<body class="login-page bg-body-secondary">

    <div class="container d-flex justify-content-center align-items-center h-100">

        <div class="card border-0 shadow-sm overflow-hidden" style="max-width:950px;width:100%;border-radius:14px;">

            <div class="row g-0">

                {{-- LEFT PANEL --}}
                <div class="col-md-6 d-none d-md-flex flex-column justify-content-center p-5"
                    style="background:#eef8f1;">

                    <div class="mb-4 text-center">

                        <img src="{{ asset('icon.png') }}" height="90px" alt="Logo">

                    </div>

                    <h3 class="fw-bold text-success mb-3">
                        My PASKAS
                    </h3>

                    <p class="text-secondary mb-0" style="font-size:14px;line-height:1.9;">

                        My PASKAS merupakan sistem distribusi beras terintegrasi
                        yang membantu pengelolaan stok, data pondok, penjadwalan,
                        dan pemantauan penyaluran secara efektif, terstruktur,
                        dan transparan untuk mendukung proses distribusi yang lebih baik.

                    </p>

                    <div class="mt-4">

                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-check-circle-fill text-success me-3"></i>
                            <span class="text-secondary">
                                Monitoring distribusi realtime
                            </span>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-check-circle-fill text-success me-3"></i>
                            <span class="text-secondary">
                                Pengelolaan stok beras
                            </span>
                        </div>

                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-3"></i>
                            <span class="text-secondary">
                                Manajemen data pondok
                            </span>
                        </div>

                    </div>

                </div>

                {{-- RIGHT PANEL --}}
                <div class="col-md-6 bg-white p-4 p-md-5 d-flex flex-column justify-content-center">

                    {{-- Mobile Logo --}}
                    <div class="text-center d-md-none mb-4">

                        <img src="{{ asset('icon.png') }}" height="60px" alt="Logo">

                        <h3 class="fw-bold text-success mt-2">
                            My PASKAS
                        </h3>

                    </div>

                    <h4 class="fw-bold text-success mb-1">
                        Masuk
                    </h4>

                    <p class="text-muted mb-4" style="font-size:14px;">
                        Silakan masuk menggunakan akun yang terdaftar
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Email
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>

                                <input type="email" name="email" class="form-control" placeholder="Masukkan email"
                                    value="{{ old('email') }}" required autofocus>

                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Password
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>

                                <input type="password" name="password" class="form-control"
                                    placeholder="Masukkan password" required>

                            </div>

                        </div>

                        <div class="mb-4">

                            <div class="form-check">

                                <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                <label class="form-check-label ms-2" for="remember">

                                    Ingat saya

                                </label>

                            </div>

                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2">

                            <i class="bi bi-box-arrow-in-right me-2"></i>

                            Masuk

                        </button>

                    </form>

                    {{-- Demo --}}
                    {{-- <div class="mt-4">

                        <small class="text-muted fw-semibold">
                            🔑 Akun Demo
                        </small>

                        <div class="mt-2 small">
                            <strong>Admin:</strong>
                            admin@paskas.my.id |
                            <code>admin123</code>
                        </div>

                        <div class="small">
                            <strong>OTA:</strong>
                            ota@ota.paskas.my.id |
                            <code>ota123</code>
                        </div>

                        <div class="small">
                            <strong>Paskas:</strong>
                            kamil@paskas.my.id |
                            <code>paskas123</code>
                        </div>

                    </div> --}}

                </div>

            </div>

        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
