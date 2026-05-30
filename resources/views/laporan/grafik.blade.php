@extends('layouts.app')
@section('title', 'Grafik Distribusi Bulanan')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">Grafik Bulanan</div>
                <div class="page-subtitle">Analisis distribusi per bulan</div>
            </div>
        </div>

        <!-- Filter Tahun -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <form method="GET" class="d-flex gap-3 align-items-center">
                    <label class="form-label mb-0 fw-bold">Tahun:</label>
                    <select name="tahun" class="form-select" onchange="this.form.submit()" style="max-width:140px">
                        @foreach ($tahunList as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Grafik Distribusi vs Masuk -->
        <div class="card mb-3">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-bar-chart-fill text-success me-2"></i>Distribusi vs Beras Masuk {{ $tahun }}
            </div>
            <div class="card-body p-3">
                <canvas id="grafikBulanan" height="250"></canvas>
            </div>
        </div>

        <!-- Top Pondok -->
        @if ($topPondok->count() > 0)
            <div class="card mb-3">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-trophy-fill text-warning me-2"></i>Pondok Penerima Terbanyak {{ $tahun }}
                </div>
                <div class="card-body p-0">
                    @foreach ($topPondok as $i => $tp)
                        <div class="d-flex align-items-center p-3 border-bottom gap-3">
                            <div
                                style="width:32px;height:32px;border-radius:50%;background:{{ $i < 3 ? '#16a34a' : '#f0fdf4' }};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.9rem;color:{{ $i < 3 ? '#fff' : '#374151' }};flex-shrink:0">
                                {{ $i + 1 }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $tp->pondok->nama }}</div>
                                <div style="font-size:0.82rem;color:#6b7280">{{ $tp->kali }}x distribusi</div>
                                <!-- Progress bar visual -->
                                <div class="progress mt-1" style="height:8px;border-radius:6px">
                                    @php $pct = $topPondok->first()->total_kg > 0 ? ($tp->total_kg / $topPondok->first()->total_kg * 100) : 0 @endphp
                                    <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bold text-success">{{ number_format($tp->total_kg) }}</div>
                                <div style="font-size:0.75rem;color:#6b7280">kg</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Grafik Donut Proporsi -->
        @if ($topPondok->count() > 0)
            <div class="card mb-3">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-pie-chart-fill text-primary me-2"></i>Proporsi per Pondok {{ $tahun }}
                </div>
                <div class="card-body p-3" style="max-height:320px">
                    <canvas id="grafikDonut" height="280"></canvas>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Bar Chart
        const ctxBar = document.getElementById('grafikBulanan');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                        label: 'Distribusi (Karung)',
                        data: {!! json_encode($dataDistribusi) !!},
                        backgroundColor: 'rgba(22,163,74,0.8)',
                        borderColor: '#16a34a',
                        borderWidth: 2,
                        borderRadius: 8,
                        order: 1,
                    },
                    {
                        label: 'Beras Masuk (Karung)',
                        data: {!! json_encode($dataMasuk) !!},
                        type: 'line',
                        borderColor: '#d97706',
                        backgroundColor: 'rgba(217,119,6,0.1)',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#d97706',
                        fill: true,
                        tension: 0.3,
                        order: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });

        // Donut Chart
        @if ($topPondok->count() > 0)
            const ctxDonut = document.getElementById('grafikDonut');
            const colors = ['#16a34a', '#22c55e', '#4ade80', '#86efac', '#bbf7d0', '#0891b2', '#06b6d4', '#67e8f9',
                '#d97706', '#f59e0b'
            ];
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: {!! $topPondok->map(fn($t) => $t->pondok->nama)->toJson() !!},
                    datasets: [{
                        data: {!! $topPondok->pluck('total_kg')->toJson() !!},
                        backgroundColor: colors.slice(0, {{ $topPondok->count() }}),
                        borderWidth: 2,
                        borderColor: '#fff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 11
                                },
                                boxWidth: 14
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.label}: ${ctx.parsed.toLocaleString('id-ID')} kg`
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endpush
