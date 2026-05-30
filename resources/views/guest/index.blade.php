<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribusi Beras - Informasi Publik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Nunito', sans-serif;
        }

        body {
            background: #f0fdf4;
        }

        /* HERO */
        .hero {
            background: linear-gradient(135deg, #14532d 0%, #166534 60%, #15803d 100%);
            color: #fff;
            padding: 40px 20px 60px;
            text-align: center;
        }

        .hero-icon {
            font-size: 4rem;
            margin-bottom: 12px;
        }

        .hero-title {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .hero-sub {
            opacity: .85;
            margin-top: 8px;
            font-size: 1rem;
        }

        .hero-btn {
            margin-top: 20px;
        }

        /* STAT CARDS */
        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 20px 16px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
        }

        .stat-card .n {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-card .l {
            font-size: 0.85rem;
            font-weight: 600;
            opacity: .7;
            margin-top: 4px;
        }

        /* PROGRESS SERI */
        .seri-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .07);
            margin-bottom: 20px;
        }

        /* ACTIVITY FEED */
        .feed-item {
            background: #fff;
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .feed-photo {
            width: 72px;
            height: 72px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .feed-icon {
            width: 72px;
            height: 72px;
            background: #dcfce7;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            flex-shrink: 0;
        }

        /* PONDOK LIST */
        .pondok-card {
            background: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: inherit;
        }

        .pondok-rank {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        /* NAV */
        .top-nav {
            background: #14532d;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-nav .brand {
            color: #fff;
            font-weight: 800;
            font-size: 1.1rem;
            text-decoration: none;
        }

        .btn-login-nav {
            background: rgba(255, 255, 255, .2);
            color: #fff !important;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
        }

        /* Tabs */
        .custom-tabs {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 0 0 8px;
            scrollbar-width: none;
        }

        .custom-tabs::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            background: #fff;
            border: 2px solid #dcfce7;
            border-radius: 20px;
            padding: 8px 18px;
            font-weight: 700;
            font-size: 0.9rem;
            color: #374151;
            white-space: nowrap;
            cursor: pointer;
        }

        .tab-btn.active {
            background: #16a34a;
            border-color: #16a34a;
            color: #fff;
        }

        .tab-content-section {
            display: none;
        }

        .tab-content-section.active {
            display: block;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #14532d;
            margin: 20px 0 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>

<body>

    <!-- TOP NAV -->
    <nav class="top-nav">
        <a href="{{ route('guest.index') }}" class="brand">🌾 Distribusi Beras</a>

        @auth
            <a href="{{ route('dashboard') }}" class="btn-login-nav">
                <i class="bi bi-speedometer2 me-1"></i>Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-login-nav">
                <i class="bi bi-box-arrow-in-right me-1"></i>Login
            </a>
        @endauth
    </nav>

    <!-- HERO -->
    <div class="hero">
        <div class="hero-icon">🌾</div>
        <div class="hero-title">Distribusi Beras<br>Pondok Pesantren</div>
        <div class="hero-sub">Transparansi penyaluran beras untuk santri dan yayasan</div>
        <div class="hero-btn">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light fw-bold px-4 py-2 rounded-pill">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-light fw-bold px-4 py-2 rounded-pill">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
                </a>
            @endauth
        </div>
    </div>

    <!-- MAIN -->
    <div class="container-fluid px-3 py-3" style="max-width:640px;margin:auto">

        <!-- STATS -->
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#16a34a,#15803d);color:#fff">
                    <div class="n">{{ number_format($totalKgSelesai) }}</div>
                    <div class="l">KG Tersalurkan</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#d97706,#b45309);color:#fff">
                    <div class="n">{{ number_format($totalSantri) }}</div>
                    <div class="l">Total Santri</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff">
                    <div class="n">{{ $totalPondok }}</div>
                    <div class="l">Pondok Penerima</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff">
                    <div class="n">{{ $totalSeri }}</div>
                    <div class="l">Seri Selesai</div>
                </div>
            </div>
        </div>

        <!-- SERI AKTIF -->
        @if ($seriAktif)
            <div class="seri-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success fs-6">🟢 Sedang Berlangsung</span>
                </div>
                <div class="fw-bold fs-5">{{ $seriAktif->nama }}</div>
                <div class="text-muted mb-2" style="font-size:0.85rem">
                    {{ $seriAktif->tanggal_mulai->isoFormat('D MMM') }} –
                    {{ $seriAktif->tanggal_selesai->isoFormat('D MMM Y') }}
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-bold">Progress Distribusi</small>
                    <small class="fw-bold text-success">{{ $progressSeri }}%</small>
                </div>
                <div class="progress" style="height:14px;border-radius:10px">
                    <div class="progress-bar bg-success" style="width:{{ $progressSeri }}%;border-radius:10px"></div>
                </div>
                <div class="row text-center g-2 mt-1">
                    <div class="col-4">
                        <small class="text-muted">Total Karung</small>
                        <div class="fw-bold text-success">{{ $seriAktif->total_karung_rencana }}</div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Sudah Salur</small>
                        <div class="fw-bold text-primary">{{ $seriAktif->total_realisasi_karung }}</div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Sisa</small>
                        <div class="fw-bold text-warning">
                            {{ $seriAktif->total_karung_rencana - $seriAktif->total_realisasi_karung }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- TABS -->
        <div class="custom-tabs mb-3">
            <button class="tab-btn active" onclick="showTab('aktivitas')">📦 Aktivitas Terbaru</button>
            <button class="tab-btn" onclick="showTab('pondok')">🕌 Daftar Pondok</button>
        </div>

        <!-- TAB: Aktivitas -->
        <div id="tab-aktivitas" class="tab-content-section active">
            <div class="section-title"><i class="bi bi-clock-history text-success"></i>Penyaluran Terbaru</div>
            @forelse($aktivitasTerbaru as $a)
                <div class="feed-item">
                    @if ($a->foto_watermark || $a->foto_bukti)
                        <img src="{{ asset('storage/' . $a->foto_watermark ?? 'storage/' . $a->foto_bukti) }}"
                            class="feed-photo">
                    @else
                        <div class="feed-icon">🌾</div>
                    @endif
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ $a->pondok->nama }}</div>
                        <div style="font-size:0.85rem;color:#6b7280">
                            {{ $a->tanggal_distribusi->locale('id')->translatedFormat('d F Y') }}
                            •
                            {{ $a->created_at->format('H:i') }} WITA
                        </div>
                        <div class="mt-1">
                            <span class="badge bg-success">{{ $a->jumlah_karung_distribusi }} karung</span>
                            <span class="badge bg-info ms-1">{{ $a->jumlah_kg_distribusi }} kg</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <div style="font-size:3rem">📦</div>
                    <div>Belum ada aktivitas penyaluran</div>
                </div>
            @endforelse
        </div>

        <!-- TAB: Pondok -->
        <div id="tab-pondok" class="tab-content-section">
            <div class="section-title"><i class="bi bi-building text-success"></i>Pondok Penerima</div>
            @foreach ($pondoks as $i => $p)
                <a href="{{ route('guest.pondok', $p) }}" class="pondok-card">
                    <div class="pondok-rank"
                        style="background:{{ $i < 3 ? '#16a34a' : '#f0fdf4' }};color:{{ $i < 3 ? '#fff' : '#374151' }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ $p->nama }}</div>
                        <div style="font-size:0.8rem;color:#6b7280">
                            <i class="bi bi-geo-alt me-1"></i>{{ $p->alamat }}
                        </div>
                        <div class="mt-1">
                            <span class="badge bg-secondary">{{ $p->jumlah_santri }} santri</span>
                            @if ($p->aktivitas_sum_jumlah_kg_distribusi > 0)
                                <span
                                    class="badge bg-success ms-1">{{ number_format($p->aktivitas_sum_jumlah_kg_distribusi) }}
                                    kg diterima</span>
                            @endif
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
            @endforeach
        </div>

        <!-- FOOTER -->
        <div class="text-center py-4 text-muted" style="font-size:0.85rem">
            <div>🌾 Sistem Distribusi Beras</div>

            <div class="mt-1">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-success btn-sm fw-bold mt-2 px-4">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard ({{ auth()->user()->role }})
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-success btn-sm fw-bold mt-2 px-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Kelola
                    </a>
                @endauth
            </div>
        </div>

    </div><!-- /container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content-section').forEach(s => s.classList.remove('active'));
            document.getElementById('tab-' + tab).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>

</html>
