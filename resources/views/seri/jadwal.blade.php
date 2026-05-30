@extends('layouts.app')
@section('title', 'Jadwal - ' . $seri->nama)

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('seri.show', $seri) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">Jadwal Distribusi</div>
                <div class="page-subtitle">{{ $seri->nama }}</div>
            </div>
        </div>

        <!-- Form Tambah Jadwal -->
        @if (Auth::user()->isAdmin())
            <div class="card mb-3">
                <div class="card-header" style="background:#f0fdf4;color:#14532d">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Jadwal Distribusi
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('seri.jadwal.store', $seri) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Pondok / Yayasan <span class="text-danger">*</span></label>
                            <select name="pondok_id" class="form-select" id="pondok-select" required>
                                <option value="">-- Pilih Pondok --</option>
                                @foreach ($pondoks->groupBy('penanggung_jawab') as $pj => $list)
                                    <optgroup label="{{ $pj }}">
                                        @foreach ($list as $p)
                                            <option value="{{ $p->id }}" data-karung="{{ $p->jatah_karung }}"
                                                data-kg="{{ $p->jatah_kg }}">
                                                {{ $p->nama }} ({{ $p->jumlah_santri }} santri)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Rencana <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control"
                                value="{{ old('tanggal', $seri->tanggal_mulai->format('Y-m-d')) }}" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Jumlah Karung <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_karung" id="input-karung" class="form-control"
                                    value="{{ old('jumlah_karung') }}" min="1" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">KG (otomatis)</label>
                                <input type="text" id="preview-kg-jadwal" class="form-control" readonly
                                    style="background:#f0fdf4;color:#16a34a;font-weight:700">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Petugas / Tim <span class="text-danger">*</span></label>
                            <select name="petugas" class="form-select" required>
                                @foreach (['Pak Kamil & Tim', 'Abah Badingsanak', 'Miftah / Amad', 'Abi Alif & Tim', 'Admin'] as $pj)
                                    <option value="{{ $pj }}">{{ $pj }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <input type="text" name="catatan" class="form-control" placeholder="Opsional">
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-lg me-2"></i>Tambah ke Jadwal
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Daftar Jadwal yang sudah ada -->
        <div class="card">
            <div class="card-header bg-white">
                <i class="bi bi-list-check me-2 text-success"></i>
                Daftar Jadwal ({{ $jadwals->count() }} titik · {{ $jadwals->sum('jumlah_karung') }} karung)
            </div>
            <div class="card-body p-0">
                @forelse($jadwals as $j)
                    <div class="d-flex align-items-center p-3 border-bottom gap-3">
                        <div class="text-center" style="min-width:44px;background:#f0fdf4;border-radius:10px;padding:6px">
                            <div style="font-size:1.2rem;font-weight:800;color:#16a34a;line-height:1">
                                {{ $j->tanggal->format('d') }}</div>
                            <div style="font-size:0.7rem;color:#6b7280">{{ $j->tanggal->isoFormat('MMM') }}</div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $j->pondok->nama }}</div>
                            <div style="font-size:0.8rem;color:#6b7280">
                                <i class="bi bi-person me-1"></i>{{ $j->petugas }}
                            </div>
                            <div class="mt-1">
                                <span class="badge bg-warning text-dark">{{ $j->jumlah_karung }} krg</span>
                                <span class="badge bg-info ms-1">{{ $j->jumlah_kg }} kg</span>
                                <span class="badge bg-{{ $j->status_badge }} ms-1">{{ $j->status_label }}</span>
                            </div>
                        </div>
                        @if ($j->status === 'belum')
                            <a href="{{ route('aktivitas.create', ['jadwal_id' => $j->id]) }}"
                                class="btn btn-sm btn-success">
                                Salurkan
                            </a>
                        @else
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">Belum ada jadwal</div>
                @endforelse
            </div>
        </div>

        @if ($jadwals->count() > 0 && $seri->status === 'draft' && Auth::user()->isAdmin())
            <div class="mt-3">
                <form method="POST" action="{{ route('seri.aktifkan', $seri) }}">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-play-circle me-2"></i>Aktifkan Seri Ini
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-isi karung dari pilihan pondok
        document.getElementById('pondok-select').addEventListener('change', function() {
            const sel = this.options[this.selectedIndex];
            const karung = sel.dataset.karung;
            if (karung) {
                document.getElementById('input-karung').value = karung;
                document.getElementById('preview-kg-jadwal').value = (parseInt(karung) * 10) + ' kg';
            }
        });

        document.getElementById('input-karung').addEventListener('input', function() {
            const val = parseInt(this.value) || 0;
            document.getElementById('preview-kg-jadwal').value = (val * 10) + ' kg';
        });
    </script>
@endpush
