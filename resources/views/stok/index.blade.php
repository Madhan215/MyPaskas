@extends('layouts.app')
@section('title', 'Stok Beras')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div class="page-title">Stok Beras</div>
                <div class="page-subtitle">Manajemen loading dan stok beras</div>
            </div>
            @if (Auth::user()->isAdmin() || Auth::user()->isOta())
                <a href="{{ route('stok.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg me-1"></i> Loading
                </a>
            @endif
        </div>

        <!-- Stok Sekarang -->
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#16a34a,#15803d)">
                    <i class="bi bi-box-seam stat-icon"></i>
                    <div class="stat-value">{{ number_format($totalStok) }}</div>
                    <div class="stat-label">Stok Karung</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#0891b2,#0e7490)">
                    <i class="bi bi-scale stat-icon"></i>
                    <div class="stat-value">{{ number_format($totalStokKg) }}</div>
                    <div class="stat-label">Stok KG</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#059669,#047857)">
                    <i class="bi bi-arrow-down-circle stat-icon"></i>
                    <div class="stat-value">{{ number_format($totalMasuk) }}</div>
                    <div class="stat-label">Total Masuk (krg)</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#d97706,#b45309)">
                    <i class="bi bi-arrow-up-circle stat-icon"></i>
                    <div class="stat-value">{{ number_format($totalKeluar) }}</div>
                    <div class="stat-label">Total Keluar (krg)</div>
                </div>
            </div>
        </div>

        <!-- Riwayat Loading -->
        <div class="card">
            <div class="card-header bg-white">
                <i class="bi bi-clock-history me-2 text-success"></i>Riwayat Loading Beras
            </div>
            <div class="card-body p-0">
                @forelse($stoks as $stok)
                    <div class="d-flex align-items-start p-3 border-bottom gap-3">
                        <div
                            style="width:48px;height:48px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0">
                            📦</div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $stok->sumber }}</div>
                            <div style="font-size:0.9rem;color:#374151" class="mt-1">
                                <span class="badge bg-success me-1">{{ $stok->jumlah_karung }} karung</span>
                                <span class="badge bg-info">{{ $stok->berat_kg }} kg</span>
                            </div>
                            <div style="font-size:0.8rem;color:#6b7280" class="mt-1">
                                <i class="bi bi-calendar3 me-1"></i>{{ $stok->tanggal->isoFormat('D MMMM Y') }}
                                &nbsp;·&nbsp; Oleh: {{ $stok->user->name }}
                            </div>
                            @if ($stok->keterangan)
                                <div style="font-size:0.85rem;color:#6b7280;font-style:italic">{{ $stok->keterangan }}
                                </div>
                            @endif
                        </div>
                        @if (Auth::user()->isAdmin())
                            <div>
                                <form id="del-stok-{{ $stok->id }}" method="POST"
                                    action="{{ route('stok.destroy', $stok) }}">
                                    @csrf @method('DELETE')
                                </form>
                                <button class="btn btn-sm btn-outline-danger btn-delete-confirm"
                                    data-form="del-stok-{{ $stok->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size:3rem;opacity:0.3"></i>
                        <div class="mt-2">Belum ada data loading</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
