@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
    <div class="py-3">
        <div class="page-title mb-1">Laporan</div>
        <div class="page-subtitle mb-4">Export & grafik distribusi</div>

        <!-- Quick links -->
        <div class="row g-3 mb-4">
            <div class="col-6">
                <a href="{{ route('laporan.grafik') }}" class="text-decoration-none">
                    <div class="card text-center p-3" style="border:2px solid #dcfce7">
                        <div style="font-size:2.5rem">📊</div>
                        <div class="fw-bold mt-1">Grafik Bulanan</div>
                        <div style="font-size:0.8rem;color:#6b7280">Distribusi per bulan</div>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('aktivitas.index') }}" class="text-decoration-none">
                    <div class="card text-center p-3" style="border:2px solid #dcfce7">
                        <div style="font-size:2.5rem">🚚</div>
                        <div class="fw-bold mt-1">Semua Aktivitas</div>
                        <div style="font-size:0.8rem;color:#6b7280">Riwayat penyaluran</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Export per Seri -->
        <div class="page-title mb-3" style="font-size:1.1rem">📄 Export Laporan PDF per Seri</div>

        @forelse($seris as $seri)
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                            <div class="fw-bold fs-5">{{ $seri->nama }}</div>
                            <div style="font-size:0.85rem;color:#6b7280">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $seri->tanggal_mulai->isoFormat('D MMM') }} –
                                {{ $seri->tanggal_selesai->isoFormat('D MMM Y') }}
                            </div>
                            <div class="mt-2 d-flex gap-2 flex-wrap">
                                <span class="badge bg-{{ $seri->status_badge }}">{{ $seri->status_label }}</span>
                                <span class="badge bg-secondary">{{ $seri->jadwals->count() }} titik</span>
                                <span class="badge bg-success">{{ $seri->total_karung_rencana }} karung</span>
                                <span class="badge bg-primary">{{ $seri->progress }}% selesai</span>
                            </div>
                        </div>
                        <a href="{{ route('laporan.pdf', $seri) }}" target="_blank" class="btn btn-danger flex-shrink-0">
                            <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <div style="font-size:3rem">📄</div>
                    <div>Belum ada seri distribusi</div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
