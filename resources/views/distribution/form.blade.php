@extends('layouts.app')
@section('title', 'Catat Penyaluran')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('aktivitas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">Catat Penyaluran</div>
                <div class="page-subtitle">Isi form setelah menyalurkan beras</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('aktivitas.store') }}" enctype="multipart/form-data"
                    id="form-aktivitas">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Pilih Jadwal Distribusi <span class="text-danger">*</span></label>
                        <select name="jadwal_id" class="form-select" id="jadwal-select" required>
                            <option value="">-- Pilih Jadwal --</option>
                            @foreach ($jadwals as $j)
                                <option value="{{ $j->id }}" data-karung="{{ $j->jumlah_karung }}"
                                    data-pondok="{{ $j->pondok->nama }}" data-alamat="{{ $j->pondok->alamat }}"
                                    data-maps="{{ $j->pondok->google_maps_url }}"
                                    {{ old('jadwal_id') == $j->id || ($jadwal && $jadwal->id == $j->id) ? 'selected' : '' }}>
                                    {{ $j->pondok->nama }} - {{ $j->tanggal->isoFormat('D MMM') }}
                                    ({{ $j->jumlah_karung }} krg)
                                </option>
                            @endforeach
                        </select>
                        @if ($jadwals->count() === 0)
                            <div class="alert alert-warning mt-2">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Tidak ada jadwal yang pending. <a href="{{ route('seri.index') }}">Buat jadwal dulu</a>
                            </div>
                        @endif
                    </div>

                    <!-- Info pondok -->
                    <div id="info-pondok" class="alert alert-success mb-4" style="display:none">
                        <div class="fw-bold" id="info-nama"></div>
                        <div id="info-detail" style="font-size:0.9rem"></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Tanggal Realisasi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_distribusi" class="form-control"
                            value="{{ old('tanggal_distribusi', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Jumlah Karung yang Disalurkan <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_karung_distribusi" id="input-karung-real" class="form-control"
                            value="{{ old('jumlah_karung_distribusi') }}" placeholder="Jumlah karung" min="1"
                            required>
                        <div class="form-text mt-1">
                            <span id="kg-real-preview" class="badge bg-info fs-6">= 0 kg</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Misal: Diterima oleh ustadz Ahmad">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- Upload Foto -->
                    <div class="mb-4">
                        <label class="form-label">Foto Bukti Penyaluran</label>
                        <div class="d-flex gap-2 mb-2">
                            <button type="button" class="btn btn-outline-success flex-fill"
                                onclick="document.getElementById('foto-kamera').click()">
                                <i class="bi bi-camera-fill me-2"></i>Ambil Foto
                            </button>
                            <button type="button" class="btn btn-outline-primary flex-fill"
                                onclick="document.getElementById('foto-galeri').click()">
                                <i class="bi bi-image me-2"></i>Dari Galeri
                            </button>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="file" id="foto-kamera" name="foto_bukti" accept="image/*" capture="environment"
                            style="display:none" onchange="previewFoto(this)">
                        <input type="file" id="foto-galeri" accept="image/*" style="display:none"
                            onchange="copyToMain(this)">

                        <!-- Preview -->
                        <div id="foto-preview" style="display:none;text-align:center">
                            <img id="preview-img" style="max-width:100%;border-radius:12px;max-height:300px">
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusFoto()">
                                    <i class="bi bi-trash me-1"></i>Hapus Foto
                                </button>
                            </div>
                        </div>

                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Foto akan otomatis diberi watermark tanggal, nama pondok, dan petugas
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100" id="btn-submit">
                        <i class="bi bi-check-circle me-2"></i>Simpan Aktivitas Penyaluran
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Jadwal select
        document.getElementById('jadwal-select').addEventListener('change', function() {
            const sel = this.options[this.selectedIndex];
            if (sel.value) {
                document.getElementById('info-nama').textContent = sel.dataset.pondok;
                document.getElementById('info-detail').innerHTML = `
            <div class="d-flex flex-column gap-1">

                <div>
                    📍 ${sel.dataset.alamat}
                </div>

                <div>
                    🌾 ${sel.dataset.karung} karung rencana
                </div>

                ${
                    sel.dataset.maps
                    ?
                    `<div>
                                        <a href="${sel.dataset.maps}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-success mt-1">
                                            <i class="bi bi-map-fill me-1"></i>Lihat Maps
                                        </a>
                                    </div>`
                    :
                    ''
                }

            </div>
        `;
                document.getElementById('info-pondok').style.display = 'block';
                if (!document.getElementById('input-karung-real').value) {
                    document.getElementById('input-karung-real').value = sel.dataset.karung;
                    document.getElementById('kg-real-preview').textContent = '= ' + (parseInt(sel.dataset.karung) *
                        10) + ' kg';
                }
            } else {
                document.getElementById('info-pondok').style.display = 'none';
            }
        });

        // Trigger if pre-selected
        if (document.getElementById('jadwal-select').value) {
            document.getElementById('jadwal-select').dispatchEvent(new Event('change'));
        }

        document.getElementById('input-karung-real').addEventListener('input', function() {
            const val = parseInt(this.value) || 0;
            document.getElementById('kg-real-preview').textContent = '= ' + (val * 10) + ' kg';
        });

        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('foto-preview').style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function copyToMain(input) {
            // Copy file ke input utama
            const dt = new DataTransfer();
            dt.items.add(input.files[0]);
            document.getElementById('foto-kamera').files = dt.files;
            previewFoto(input);
        }

        function hapusFoto() {
            document.getElementById('foto-kamera').value = '';
            document.getElementById('foto-galeri').value = '';
            document.getElementById('foto-preview').style.display = 'none';
        }

        // Loading on submit
        document.getElementById('form-aktivitas').addEventListener('submit', function() {
            document.getElementById('loading-overlay').classList.add('show');
            document.getElementById('btn-submit').disabled = true;
            document.getElementById('btn-submit').innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
        });
    </script>
@endpush
