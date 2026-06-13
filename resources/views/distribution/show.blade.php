@extends('layouts.app')
@section('title', 'Detail Penyaluran')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('aktivitas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">Detail Penyaluran</div>
                <div class="page-subtitle">{{ $aktivitas->tanggal_distribusi->locale('id')->translatedFormat('d M Y') }}
                </div>
            </div>
        </div>

        <!-- Foto Bukti -->
        @if ($aktivitas->foto_watermark || $aktivitas->foto_bukti)
            <div class="card mb-3">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-image text-success me-2"></i>Foto Bukti Penyaluran
                </div>
                <div class="card-body p-2 text-center">
                    <img src="{{ asset('storage/' . $aktivitas->foto_watermark ?? 'storage/' . $aktivitas->foto_bukti) }}"
                        class="img-fluid rounded-3" style="max-height: 400px; width: 100%; object-fit: contain;"
                        alt="Foto Bukti">
                    @if ('storage/' . $aktivitas->foto_watermark && 'storage/' . $aktivitas->foto_bukti)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $aktivitas->foto_bukti) }}" target="_blank"
                                class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-image me-1"></i>Lihat Foto Asli
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card mb-3" style="border: 2px dashed #d1fae5;">
                <div class="card-body text-center py-4 text-muted">
                    <i class="bi bi-camera" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    <div class="mt-2">Tidak ada foto bukti</div>
                </div>
            </div>
        @endif

        <!-- Info Utama -->
        <div class="card mb-3">
            <div class="card-header" style="background: #f0fdf4; color: #14532d;">
                <i class="bi bi-info-circle-fill me-2"></i>Informasi Penyaluran
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless mb-0">
                    <tr class="border-bottom">
                        <td class="fw-bold ps-3" style="width: 40%; color: #6b7280; font-size: 0.9rem;">Pondok / Yayasan
                        </td>
                        <td class="fw-bold pe-3">{{ $aktivitas->pondok->nama }}</td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="fw-bold ps-3" style="color: #6b7280; font-size: 0.9rem;">Alamat</td>
                        <td class="pe-3">{{ $aktivitas->pondok->alamat }}</td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="fw-bold ps-3" style="color: #6b7280; font-size: 0.9rem;">Seri</td>
                        <td class="pe-3">
                            <span class="badge bg-primary">{{ $aktivitas->seri->nama }}</span>
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="fw-bold ps-3" style="color: #6b7280; font-size: 0.9rem;">Tanggal</td>
                        <td class="pe-3">{{ $aktivitas->tanggal_distribusi->locale('id')->translatedFormat('l, d F Y') }}
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="fw-bold ps-3" style="color: #6b7280; font-size: 0.9rem;">Jam</td>
                        <td class="pe-3">{{ $aktivitas->jam_distribusi ?? '-' }}</td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="fw-bold ps-3" style="color: #6b7280; font-size: 0.9rem;">Jumlah Karung</td>
                        <td class="pe-3">
                            <span class="badge bg-success fs-6">{{ $aktivitas->jumlah_karung_distribusi }} karung</span>
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="fw-bold ps-3" style="color: #6b7280; font-size: 0.9rem;">Berat (KG)</td>
                        <td class="pe-3">
                            <span class="badge bg-info fs-6">{{ $aktivitas->jumlah_kg_distribusi }} kg</span>
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="fw-bold ps-3" style="color: #6b7280; font-size: 0.9rem;">Dicatat Oleh</td>
                        <td class="pe-3">
                            <i class="bi bi-person-fill me-1 text-success"></i>{{ $aktivitas->user->name }}
                            <div style="font-size: 0.8rem; color: #6b7280;">{{ $aktivitas->user->role_label }}</div>
                        </td>
                    </tr>
                    @if ($aktivitas->catatan)
                        <tr>
                            <td class="fw-bold ps-3" style="color: #6b7280; font-size: 0.9rem;">Catatan</td>
                            <td class="pe-3" style="font-style: italic;">{{ $aktivitas->catatan }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Perbandingan Rencana vs Realisasi -->
        @if ($aktivitas->jadwal)
            <div class="card mb-3">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-bar-chart-line me-2 text-primary"></i>Rencana vs Realisasi
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background: #eff6ff;">
                                <div style="font-size: 2rem; font-weight: 800; color: #2563eb;">
                                    {{ $aktivitas->jadwal->jumlah_karung_rencana }}
                                </div>
                                <div style="font-size: 0.85rem; color: #6b7280;">Rencana (Karung)</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background: #f0fdf4;">
                                <div style="font-size: 2rem; font-weight: 800; color: #16a34a;">
                                    {{ $aktivitas->jumlah_karung_distribusi }}
                                </div>
                                <div style="font-size: 0.85rem; color: #6b7280;">Realisasi (Karung)</div>
                            </div>
                        </div>
                    </div>

                    @php
                        $selisih = $aktivitas->jumlah_karung_distribusi - $aktivitas->jadwal->jumlah_karung_rencana;
                    @endphp

                    @if ($selisih !== 0)
                        <div class="alert {{ $selisih > 0 ? 'alert-warning' : 'alert-info' }} mt-3 mb-0">
                            <i class="bi bi-{{ $selisih > 0 ? 'arrow-up' : 'arrow-down' }}-circle me-2"></i>
                            @if ($selisih > 0)
                                Realisasi <strong>lebih {{ $selisih }} karung</strong> dari rencana
                            @else
                                Realisasi <strong>kurang {{ abs($selisih) }} karung</strong> dari rencana
                            @endif
                        </div>
                    @else
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="bi bi-check-circle me-2"></i>Realisasi sesuai rencana ✓
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Tombol Aksi -->
        <div class="d-grid gap-2">
            <a href="{{ route('aktivitas.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
            </a>
            @if ($aktivitas->foto_watermark)
                <a href="{{ asset('storage/' . $aktivitas->foto_watermark) }}" download
                    class="btn btn-outline-success btn-lg">
                    <i class="bi bi-download me-2"></i>Unduh Foto Bukti
                </a>
            @endif
        </div>
    </div>
@endsection
