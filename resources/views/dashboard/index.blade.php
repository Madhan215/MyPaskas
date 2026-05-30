@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="py-3">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div class="page-title">Dashboard</div>
                <div class="page-subtitle">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </div>
            </div>
            @if ($seriAktif)
                <span class="badge bg-success fs-6">
                    <i class="bi bi-circle-fill" style="font-size:0.5rem;vertical-align:middle"></i>
                    {{ $seriAktif->nama }}
                </span>
            @endif
        </div>

        <!-- Stat Cards Row 1 -->
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#16a34a,#15803d)">
                    <i class="bi bi-box-seam stat-icon"></i>
                    <div class="stat-value">{{ number_format($stokKarung) }}</div>
                    <div class="stat-label">Stok Karung</div>
                    <div style="font-size:0.8rem;opacity:0.8">{{ number_format($stokKg) }} kg</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#d97706,#b45309)">
                    <i class="bi bi-building stat-icon"></i>
                    <div class="stat-value">{{ $totalPondok }}</div>
                    <div class="stat-label">Total Pondok</div>
                    <div style="font-size:0.8rem;opacity:0.8">{{ number_format($totalSantri) }} santri</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#2563eb,#1d4ed8)">
                    <i class="bi bi-clock-history stat-icon"></i>
                    <div class="stat-value">{{ $jadwalBelum }}</div>
                    <div class="stat-label">Belum Disalurkan</div>
                    <div style="font-size:0.8rem;opacity:0.8">jadwal pending</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#7c3aed,#6d28d9)">
                    <i class="bi bi-check-circle stat-icon"></i>
                    <div class="stat-value">{{ $jadwalSelesai }}</div>
                    <div class="stat-label">Sudah Disalurkan</div>
                    <div style="font-size:0.8rem;opacity:0.8">{{ number_format($totalKgDisalurkan) }} kg total</div>
                </div>
            </div>
        </div>

        <!-- Grafik Harian -->
        <div class="card mb-3">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-bar-chart-fill text-success"></i>
                Distribusi 7 Hari Terakhir
            </div>
            <div class="card-body p-3">
                @if ($grafikHarian->count() > 0)
                    <canvas id="grafikHarian" height="200"></canvas>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-bar-chart" style="font-size:3rem;opacity:0.3"></i>
                        <div class="mt-2">Belum ada data distribusi</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Progress Seri Aktif -->
        @if ($seriAktif)
            <div class="card mb-3">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-check text-success"></i>
                        {{ $seriAktif->nama }}
                    </div>
                    <a href="{{ route('seri.show', $seriAktif) }}" class="btn btn-sm btn-outline-success">Detail</a>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-bold">Progress Distribusi</span>
                        <span class="fw-bold text-success">{{ $seriAktif->progress }}%</span>
                    </div>
                    <div class="progress mb-3" style="height:16px;border-radius:10px">
                        <div class="progress-bar bg-success" style="width:{{ $seriAktif->progress }}%;border-radius:10px">
                        </div>
                    </div>
                    <div class="row text-center g-2">
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-2">
                                <div class="fw-800 text-success fs-5">{{ $seriAktif->total_karung }}</div>
                                <div style="font-size:0.8rem;color:#6b7280">Karung Plan</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-2">
                                <div class="fw-800 text-primary fs-5">{{ $seriAktif->total_realisasi_karung }}</div>
                                <div style="font-size:0.8rem;color:#6b7280">Realisasi</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-2">
                                <div class="fw-800 text-warning fs-5">
                                    {{ $seriAktif->total_karung - $seriAktif->total_realisasi_karung }}</div>
                                <div style="font-size:0.8rem;color:#6b7280">Sisa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Jadwal Mendatang -->
        @if ($jadwalMendatang->count() > 0)
            <div class="card mb-3">
                <div class="card-header bg-white d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-event text-warning"></i>
                    Jadwal Mendatang
                </div>
                <div class="card-body p-0">
                    @foreach ($jadwalMendatang as $j)
                        <div class="d-flex align-items-center p-3 border-bottom gap-3">
                            <div class="text-center"
                                style="min-width:48px;background:#f0fdf4;border-radius:10px;padding:8px">
                                <div style="font-size:1.3rem;font-weight:800;color:#16a34a;line-height:1">
                                    {{ $j->tanggal->format('d') }}</div>
                                <div style="font-size:0.7rem;color:#6b7280">{{ $j->tanggal->isoFormat('MMM') }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $j->pondok->nama }}</div>
                                <div style="font-size:0.85rem;color:#6b7280">
                                    <i class="bi bi-person-fill me-1"></i>{{ $j->petugas }}
                                    &nbsp;·&nbsp;
                                    <i class="bi bi-box-seam me-1"></i>{{ $j->jumlah_karung }} karung
                                </div>
                            </div>
                            <a href="{{ route('aktivitas.create', ['jadwal_id' => $j->id]) }}"
                                class="btn btn-sm btn-success">
                                Salurkan
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Aktivitas Terbaru -->
        @if ($aktivitasTerbaru->count() > 0)
            <div class="card mb-3">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-primary"></i>
                        Aktivitas Terbaru
                    </div>
                    <a href="{{ route('aktivitas.index') }}" class="btn btn-sm btn-outline-primary">Semua</a>
                </div>
                <div class="card-body p-0">
                    @foreach ($aktivitasTerbaru as $a)
                        <a href="{{ route('aktivitas.show', $a) }}"
                            class="d-flex align-items-center p-3 border-bottom gap-3 text-decoration-none text-dark">
                            <div
                                style="width:44px;height:44px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">
                                🌾
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $a->pondok->nama }}</div>
                                <div style="font-size:0.85rem;color:#6b7280">
                                    {{ $a->tanggal_distribusi->isoFormat('D MMM Y') }}
                                    &nbsp;·&nbsp; {{ $a->jumlah_karung_distribusi }} karung
                                </div>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="row g-3">
            <div class="col-6">
                <a href="{{ route('aktivitas.create') }}" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-truck me-2"></i>Catat Penyaluran
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('stok.create') }}" class="btn btn-warning btn-lg w-100">
                    <i class="bi bi-plus-circle me-2"></i>Loading Beras
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        @if ($grafikHarian->count() > 0)
            const ctx = document.getElementById('grafikHarian');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! $grafikHarian->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->isoFormat('D MMM'))->toJson() !!},
                    datasets: [{
                        label: 'Karung Disalurkan',
                        data: {!! $grafikHarian->pluck('total_karung')->toJson() !!},
                        backgroundColor: 'rgba(22,163,74,0.7)',
                        borderColor: '#16a34a',
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endpush
