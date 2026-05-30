@extends('layouts.app')
@section('title', 'Detail Seri - ' . $seri->nama)

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('seri.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">{{ $seri->nama }}</div>
                <div class="page-subtitle">
                    <span class="badge bg-{{ $seri->status_badge }}">{{ $seri->status_label }}</span>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="card mb-3" style="background:linear-gradient(135deg,#14532d,#166534);color:#fff">
            <div class="card-body">
                <div class="row text-center g-2">
                    <div class="col-3">
                        <div style="font-size:1.6rem;font-weight:800">{{ $jadwals->count() }}</div>
                        <div style="opacity:0.8;font-size:0.75rem">Titik</div>
                    </div>
                    <div class="col-3">
                        <div style="font-size:1.6rem;font-weight:800">{{ $seri->total_karung_rencana }}</div>
                        <div style="opacity:0.8;font-size:0.75rem">Krg Plan</div>
                    </div>
                    <div class="col-3">
                        <div style="font-size:1.6rem;font-weight:800">{{ $seri->total_realisasi_karung }}</div>
                        <div style="opacity:0.8;font-size:0.75rem">Realisasi</div>
                    </div>
                    <div class="col-3">
                        <div style="font-size:1.6rem;font-weight:800">{{ $seri->progress }}%</div>
                        <div style="opacity:0.8;font-size:0.75rem">Progress</div>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('seri.jadwal', $seri) }}" class="btn btn-primary w-100 mb-3">
            <i class="bi bi-calendar-plus me-2"></i>Kelola Jadwal Distribusi
        </a>

        <!-- Daftar Jadwal -->
        @php $kelompoks = $jadwals->groupBy(fn($j) => $j->pondok->penanggung_jawab); @endphp

        @foreach ($kelompoks as $kelompok => $items)
            <div class="card mb-3">
                <div class="card-header" style="background:#f0fdf4;color:#14532d">
                    <i class="bi bi-people-fill me-2"></i>{{ $kelompok }}
                    <span class="badge bg-warning text-dark ms-2">{{ $items->sum('jumlah_karung_rencana') }} karung</span>
                </div>
                <div class="card-body p-0">
                    @foreach ($items as $j)
                        <div class="d-flex align-items-center p-3 border-bottom gap-3">
                            <div class="text-center" style="min-width:44px">
                                <div style="font-size:1.2rem;font-weight:800;color:#16a34a;line-height:1">
                                    {{ $j->tanggal->format('d') }}
                                </div>
                                <div style="font-size:0.7rem;color:#6b7280">{{ $j->tanggal->isoFormat('MMM') }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $j->pondok->nama }}</div>
                                <div style="font-size:0.8rem;color:#6b7280">
                                    {{ $j->jumlah_karung }} karung · {{ $j->jumlah_kg_rencana }} kg
                                </div>
                            </div>
                            <span class="badge bg-{{ $j->status_badge }}">{{ $j->status_label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
