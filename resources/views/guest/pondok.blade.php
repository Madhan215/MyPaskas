<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pondok->nama }} - Distribusi Beras</title>
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

        .top-nav {
            background: #14532d;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-nav a {
            color: #fff;
            text-decoration: none;
        }

        .top-nav .title {
            color: #fff;
            font-weight: 800;
            font-size: 1rem;
        }

        .hero {
            background: linear-gradient(135deg, #14532d, #166534);
            color: #fff;
            padding: 24px 20px;
        }

        .stat-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 16px;
        }

        .stat-box {
            background: rgba(255, 255, 255, .15);
            border-radius: 12px;
            padding: 12px;
            text-align: center;
        }

        .stat-box .n {
            font-size: 1.6rem;
            font-weight: 800;
        }

        .stat-box .l {
            font-size: 0.75rem;
            opacity: .8;
        }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            margin-bottom: 12px;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <nav class="top-nav">
        <a href="{{ route('guest.index') }}"><i class="bi bi-arrow-left fs-5"></i></a>
        <div class="title">Detail Pondok</div>
    </nav>

    <div class="hero">
        <div style="font-size:2rem">🕌</div>
        <div style="font-size:1.3rem;font-weight:800;margin-top:6px">{{ $pondok->nama }}</div>
        <div style="opacity:.85;margin-top:4px">
            <i class="bi bi-geo-alt me-1"></i>{{ $pondok->alamat }}
        </div>
        @if ($pondok->google_maps_url)
            <a href="{{ $pondok->google_maps_url }}" target="_blank" class="btn btn-light btn-sm fw-bold mt-3 px-3">
                <i class="bi bi-geo-alt-fill me-2 text-danger"></i>Buka di Google Maps
            </a>
        @endif

        <div class="stat-row">
            <div class="stat-box">
                <div class="n">{{ $pondok->jumlah_santri }}</div>
                <div class="l">Santri</div>
            </div>
            <div class="stat-box">
                <div class="n">{{ number_format($totalKg) }}</div>
                <div class="l">Total KG</div>
            </div>
            <div class="stat-box">
                <div class="n">{{ $totalTerima }}</div>
                <div class="l">Kali Terima</div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-3 py-3" style="max-width:640px;margin:auto">

        @if ($pondok->penanggung_jawab)
            <div class="card">
                <div class="card-body">
                    <div style="font-size:0.85rem;color:#6b7280">Tim Penanggung Jawab</div>
                    <div class="fw-bold fs-5">👥 {{ $pondok->penanggung_jawab }}</div>
                </div>
            </div>
        @endif

        <!-- Riwayat -->
        <div style="font-size:1rem;font-weight:800;color:#14532d;margin:16px 0 10px">
            <i class="bi bi-clock-history me-2 text-success"></i>Riwayat Penerimaan
        </div>

        @forelse($aktivitas as $a)
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold">{{ $a->seri->nama }}</div>
                            <div style="font-size:0.85rem;color:#6b7280">
                                <i class="bi bi-calendar3 me-1"></i>{{ $a->tanggal_distribusi->isoFormat('D MMMM Y') }}
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">{{ $a->jumlah_karung_distribusi }} karung</span><br>
                            <span class="badge bg-info mt-1">{{ $a->jumlah_kg_distribusi }} kg</span>
                        </div>
                    </div>
                    @if ($a->foto_watermark || $a->foto_bukti)
                        <img src="{{ asset($a->foto_watermark ?? $a->foto_bukti) }}" class="img-fluid rounded-3 mt-2"
                            style="max-height:200px;width:100%;object-fit:cover">
                    @endif
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-4 text-muted">
                    Belum ada riwayat penerimaan
                </div>
            </div>
        @endforelse

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
