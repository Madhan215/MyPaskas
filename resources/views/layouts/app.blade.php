<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Paskas') | Sistem Distribusi Beras</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --hijau: #16a34a;
            --hijau-gelap: #15803d;
            --hijau-muda: #dcfce7;
            --kuning: #f59e0b;
            --merah: #dc2626;
            --abu: #6b7280;
            --radius: 14px;
        }

        * {
            font-family: 'Nunito', sans-serif;
        }

        body {
            background: #f0fdf4;
            font-size: 16px;
        }

        /* Navbar */
        .navbar-brand {
            font-size: 1.2rem;
            font-weight: 800;
        }

        .navbar {
            background: var(--hijau-gelap) !important;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: -260px;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #14532d 0%, #166534 100%);
            z-index: 1050;
            transition: left 0.3s ease;
            padding-top: 60px;
            overflow-y: auto;
        }

        .sidebar.open {
            left: 0;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        .sidebar-overlay.show {
            display: block;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-size: 1rem;
            font-weight: 600;
            padding: 14px 20px;
            border-radius: 10px;
            margin: 2px 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15) !important;
            color: #fff !important;
        }

        .sidebar .nav-link i {
            font-size: 1.3rem;
        }

        .sidebar-header {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 8px;
        }

        .sidebar-header .user-name {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
        }

        .sidebar-header .user-role {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
        }

        /* Main content */
        .main-content {
            padding-top: 70px;
            padding-bottom: 30px;
            min-height: 100vh;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--radius);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
        }

        .card-header {
            border-radius: var(--radius) var(--radius) 0 0 !important;
            font-weight: 700;
            font-size: 1rem;
        }

        /* Stat cards */
        .stat-card {
            border-radius: var(--radius);
            padding: 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .stat-card .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
            position: absolute;
            right: 16px;
            top: 16px;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 4px;
        }

        /* Buttons - bigger for elderly */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            padding: 10px 18px;
        }

        .btn-lg {
            padding: 14px 24px;
            font-size: 1.1rem;
        }

        .btn-success {
            background: var(--hijau) !important;
            border-color: var(--hijau) !important;
        }

        .btn-success:hover {
            background: var(--hijau-gelap) !important;
        }

        /* Form controls - bigger touch targets */
        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            border: 2px solid #d1fae5;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--hijau);
            box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.25);
        }

        .form-label {
            font-weight: 700;
            font-size: 1rem;
            color: #374151;
        }

        /* Table */
        .table {
            font-size: 0.95rem;
        }

        .table th {
            background: #f0fdf4;
            font-weight: 700;
        }

        .table td {
            vertical-align: middle;
        }

        /* Badge */
        .badge {
            font-size: 0.85rem;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 600;
        }

        /* Alert */
        .alert {
            border-radius: var(--radius);
            font-weight: 600;
            border: none;
        }

        /* Page title */
        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #14532d;
            margin-bottom: 4px;
        }

        .page-subtitle {
            color: var(--abu);
            font-size: 0.9rem;
        }

        /* Bottom nav for mobile */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 2px solid #dcfce7;
            z-index: 1000;
            padding: 6px 0 env(safe-area-inset-bottom, 6px);
        }

        .bottom-nav .nav-item a {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--abu);
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 700;
            gap: 2px;
            padding: 4px 0;
        }

        .bottom-nav .nav-item a.active,
        .bottom-nav .nav-item a:hover {
            color: var(--hijau);
        }

        .bottom-nav .nav-item a i {
            font-size: 1.4rem;
        }

        @media (max-width: 768px) {
            .bottom-nav {
                display: flex;
            }

            .main-content {
                padding-bottom: 80px;
            }

            .stat-card .stat-value {
                font-size: 1.6rem;
            }
        }

        /* Loading overlay */
        #loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        #loading-overlay.show {
            display: flex;
        }

        .spinner-big {
            width: 60px;
            height: 60px;
            border-width: 5px;
            color: #fff;
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Loading Overlay -->
    <div id="loading-overlay">
        <div class="spinner-border spinner-big" role="status"></div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('profil.show') }}" style="text-decoration:none;color:inherit;">
                <div class="d-flex align-items-center gap-3 mb-2" style="cursor:pointer;">
                    <div
                        style="width:42px;height:42px;border-radius:50%;overflow:hidden;border:2px solid rgba(255,255,255,0.3);flex-shrink:0">

                        <img src="{{ Auth::user()->foto_profil ? asset(Auth::user()->foto_profil) : asset('uploads/profil/default.png') }}"
                            alt="Foto Profil" style="width:100%;height:100%;object-fit:cover">

                    </div>

                    <div>
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">{{ Auth::user()->role_label }}</div>
                    </div>
                </div>
            </a>
        </div>

        <nav>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('stok.index') }}" class="nav-link {{ request()->routeIs('stok.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Stok Beras
            </a>
            <a href="{{ route('seri.index') }}" class="nav-link {{ request()->routeIs('seri.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Seri & Jadwal
            </a>
            <a href="{{ route('aktivitas.index') }}"
                class="nav-link {{ request()->routeIs('aktivitas.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> Penyaluran
            </a>
            <a href="{{ route('guest.index') }}" class="nav-link {{ request()->routeIs('guest.*') ? 'active' : '' }}">
                <i class="bi bi-search"></i> Laman Publik
            </a>

            <a href="{{ route('laporan.index') }}"
                class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="bi bi-download"></i> Export Data
            </a>

            @auth
                @if (auth()->user()->role === 'admin')
                    <hr style="border-color:rgba(255,255,255,0.2);margin:10px 20px">

                    {{-- 🧩 MENU TAMBAHAN --}}
                    <a href="{{ route('pondok.index') }}"
                        class="nav-link {{ request()->routeIs('pondok.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> Data Pondok
                    </a>

                    <a href="{{ route('user.index') }}"
                        class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Manajemen User
                    </a>
                @endif
            @endauth

            <hr style="border-color:rgba(255,255,255,0.2);margin:10px 20px">

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link border-0 w-100 text-start"
                    style="background:none;cursor:pointer;">
                    <i class="bi bi-box-arrow-left"></i> Keluar
                </button>
            </form>
        </nav>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-dark fixed-top px-3">
        <button class="btn btn-link text-white p-0 me-3" onclick="toggleSidebar()">
            <i class="bi bi-list" style="font-size:1.8rem"></i>
        </button>
        <span class="navbar-brand mb-0">🌾 My Paskas</span>
        <div class="ms-auto d-none d-md-flex align-items-center gap-2">
            <a href="{{ route('aktivitas.create') }}" class="btn btn-warning btn-sm fw-bold">

                <i class="bi bi-plus-lg"></i>
                Salurkan

            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content container-fluid px-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible mt-2" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible mt-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bottom Nav (Mobile) -->
    <div class="bottom-nav">
        <div class="nav-item col">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Beranda
            </a>
        </div>
        <div class="nav-item col">
            <a href="{{ route('stok.index') }}" class="{{ request()->routeIs('stok.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Stok
            </a>
        </div>
        <div class="nav-item col">
            <a href="{{ route('aktivitas.create') }}"
                class="{{ request()->routeIs('aktivitas.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle-fill" style="font-size:2rem;color:var(--hijau)"></i>
            </a>
        </div>
        <div class="nav-item col">
            <a href="{{ route('seri.index') }}" class="{{ request()->routeIs('seri.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Seri
            </a>
        </div>
        <div class="nav-item col">
            <a href="{{ route('aktivitas.index') }}"
                class="{{ request()->routeIs('aktivitas.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> Salur
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }

        // SweetAlert for delete confirmations
        document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = document.getElementById(this.dataset.form);
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'fs-5'
                    }
                }).then(r => {
                    if (r.isConfirmed) form.submit();
                });
            });
        });

        // Show SweetAlert for flash messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                timer: 2500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: @json(session('error')),
                timer: 3000
            });
        @endif
    </script>

    @stack('scripts')
</body>

</html>
