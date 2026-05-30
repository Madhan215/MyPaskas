@extends('layouts.app')
@section('title', 'Buat Seri Distribusi')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('seri.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">Buat Seri Baru</div>
                <div class="page-subtitle">Setiap seri = 1 periode distribusi (biasanya 1 bulan)</div>
            </div>
        </div>

        <div class="card mb-3" style="background:#fffbeb;border:2px solid #fde68a">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-2">📋 Alur Kerja:</h6>
                <div class="d-flex flex-column gap-2" style="font-size:0.9rem">
                    <div><span class="badge bg-success me-2">1</span> Buat Seri (periode bulan)</div>
                    <div><span class="badge bg-primary me-2">2</span> Tambah jadwal distribusi (tiap pondok)</div>
                    <div><span class="badge bg-warning text-dark me-2">3</span> Aktifkan seri</div>
                    <div><span class="badge bg-secondary me-2">4</span> Paskas mulai mencatat penyaluran</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('seri.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Nama Seri <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                            placeholder="Contoh: Seri 3 - Maret 2025" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select" required>
                                @foreach (range(1, 12) as $b)
                                    <option value="{{ $b }}"
                                        {{ old('bulan', date('n')) == $b ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create(null, $b)->isoFormat('MMMM') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select" required>
                                @foreach (range(2024, 2027) as $t)
                                    <option value="{{ $t }}"
                                        {{ old('tahun', date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control"
                                value="{{ old('tanggal_mulai', date('Y-m-01')) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control"
                                value="{{ old('tanggal_selesai', date('Y-m-t')) }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-check-lg me-2"></i>Buat Seri & Lanjut ke Jadwal
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
