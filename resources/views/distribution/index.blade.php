@extends('layouts.app')
@section('title', 'Aktivitas Penyaluran')

@section('content')
    <style>
        .pagination svg {
            width: 1rem !important;
            height: 1rem !important;
        }
    </style>

    <div class="py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div class="page-title">Penyaluran</div>
                <div class="page-subtitle">Riwayat aktivitas distribusi beras</div>
            </div>
            <a href="{{ route('aktivitas.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> Catat
            </a>
        </div>

        @forelse($aktivitas as $a)
            <a href="{{ route('aktivitas.show', $a) }}" class="text-decoration-none text-dark">
                <div class="card mb-2">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start gap-3">
                            @if ($a->foto_watermark || $a->foto_bukti)
                                <img src="{{ asset($a->foto_watermark ?? $a->foto_bukti) }}"
                                    style="width:64px;height:64px;object-fit:cover;border-radius:10px;flex-shrink:0">
                            @else
                                <div
                                    style="width:64px;height:64px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;flex-shrink:0">
                                    🌾</div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $a->pondok->nama }}</div>
                                <div style="font-size:0.85rem;color:#6b7280">
                                    <i
                                        class="bi bi-calendar3 me-1"></i>{{ $a->tanggal_distribusi->locale('id')->translatedFormat('l, d F Y') }}
                                </div>
                                <div class="mt-1 d-flex gap-2 flex-wrap">
                                    <span class="badge bg-success">{{ $a->jumlah_karung_distribusi }} karung</span>
                                    <span class="badge bg-info">{{ $a->jumlah_kg_distribusi }} kg</span>
                                    <span class="badge bg-secondary">{{ $a->seri->nama }}</span>
                                </div>
                                <div style="font-size:0.8rem;color:#6b7280;margin-top:4px">
                                    <i class="bi bi-person me-1"></i>{{ $a->user->name }}

                                    <span class="mx-1">•</span>

                                    <i class="bi bi-clock-history me-1"></i>
                                    {{ $a->created_at->locale('id')->translatedFormat('H:i') }} WITA
                                </div>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <div style="font-size:4rem">🚚</div>
                    <div class="fw-bold mt-2">Belum ada aktivitas penyaluran</div>
                    <a href="{{ route('aktivitas.create') }}" class="btn btn-success mt-3">
                        Catat Penyaluran Pertama
                    </a>
                </div>
            </div>
        @endforelse

        <div class="mt-3">{{ $aktivitas->links() }}</div>
    </div>
@endsection
