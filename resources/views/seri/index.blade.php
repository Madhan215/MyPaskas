@extends('layouts.app')
@section('title', 'Seri Distribusi')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div class="page-title">Seri Distribusi</div>
                <div class="page-subtitle">Kelola periode/jadwal distribusi</div>
            </div>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('seri.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg me-1"></i> Buat Seri
                </a>
            @endif
        </div>

        @forelse($seris as $seri)
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="fw-bold fs-5">{{ $seri->nama }}</div>
                                <span class="badge bg-{{ $seri->status_badge }}">{{ $seri->status_label }}</span>
                            </div>
                            <div style="font-size:0.9rem;color:#6b7280">
                                <i class="bi bi-calendar-range me-1"></i>
                                {{ $seri->tanggal_mulai->isoFormat('D MMM') }} -
                                {{ $seri->tanggal_selesai->isoFormat('D MMM Y') }}
                            </div>

                            @if ($seri->jadwals->count() > 0)
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="fw-bold text-muted">Progress</small>
                                        <small class="fw-bold text-success">{{ $seri->progress }}%</small>
                                    </div>
                                    <div class="progress" style="height:10px;border-radius:10px">
                                        <div class="progress-bar bg-success" style="width:{{ $seri->progress }}%"></div>
                                    </div>
                                </div>
                                <div class="row g-2 mt-1 text-center">
                                    <div class="col-3">
                                        <div class="bg-light rounded p-1">
                                            <div class="fw-bold text-success">{{ $seri->jadwals->count() }}</div>
                                            <div style="font-size:0.7rem;color:#6b7280">Titik</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="bg-light rounded p-1">
                                            <div class="fw-bold">{{ $seri->total_karung_rencana }}</div>
                                            <div style="font-size:0.7rem;color:#6b7280">Krg Plan</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="bg-light rounded p-1">
                                            <div class="fw-bold text-primary">{{ $seri->total_realisasi_karung }}</div>
                                            <div style="font-size:0.7rem;color:#6b7280">Realisasi</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="bg-light rounded p-1">
                                            <div class="fw-bold text-warning">
                                                {{ $seri->total_karung_rencana - $seri->total_realisasi_karung }}</div>
                                            <div style="font-size:0.7rem;color:#6b7280">Sisa</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning mt-2 mb-0 py-2 px-3" style="font-size:0.85rem">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Belum ada jadwal distribusi
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('seri.show', $seri) }}" class="btn btn-outline-success btn-sm flex-fill">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                        <a href="{{ route('seri.jadwal', $seri) }}" class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="bi bi-calendar-plus me-1"></i>Jadwal
                        </a>
                        @if (($seri->status === 'draft' && Auth::user()->isAdmin()) || ($seri->status === 'selesai' && Auth::user()->isAdmin()))
                            <form method="POST" action="{{ route('seri.aktifkan', $seri) }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-play-circle me-1"></i>Aktifkan
                                </button>
                            </form>
                        @elseif($seri->status === 'aktif' && Auth::user()->isAdmin())
                            <form method="POST" action="{{ route('seri.selesai', $seri) }}">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-stop-circle me-1"></i>Selesai
                                </button>
                            </form>
                            {{-- @elseif($seri->status === 'selesai' && Auth::user()->isAdmin())
                            <form method="POST" action="{{ route('seri.aktifkan_lagi', $seri) }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-stop-circle me-1"></i>Aktifkan
                                </button>
                            </form> --}}
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <div style="font-size:4rem">📅</div>
                    <div class="fw-bold mt-2">Belum ada seri distribusi</div>
                    <div class="text-muted mb-3">Buat seri terlebih dahulu</div>
                    <a href="{{ route('seri.create') }}" class="btn btn-success">Buat Seri Pertama</a>
                </div>
            </div>
        @endforelse
    </div>
@endsection
