@extends('layouts.app')
@section('title', $pondok ? 'Edit Pondok' : 'Tambah Pondok')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('pondok.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">{{ $pondok ? 'Edit Pondok' : 'Tambah Pondok' }}</div>
                <div class="page-subtitle">Isi data pondok/yayasan penerima</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ $pondok ? route('pondok.update', $pondok) : route('pondok.store') }}">
                    @csrf
                    @if ($pondok)
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label class="form-label">Nama Pondok / Yayasan <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $pondok?->nama) }}" placeholder="Contoh: Ponpes Al Aminiah">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                        <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                            value="{{ old('alamat', $pondok?->alamat) }}" placeholder="Contoh: Jl. Basirih No. 12">
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label">Jumlah Santri <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_santri" class="form-control"
                                value="{{ old('jumlah_santri', $pondok?->jumlah_santri ?? 0) }}" min="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Jumlah Pengasuh</label>
                            <input type="number" name="jumlah_pengasuh" class="form-control"
                                value="{{ old('jumlah_pengasuh', $pondok?->jumlah_pengasuh ?? 0) }}" min="0">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Penanggung Jawab / Tim</label>
                        <select name="penanggung_jawab" class="form-select">
                            <option value="">-- Pilih Tim --</option>
                            @foreach (['Pak Kamil & Tim', 'Abah Badingsanak', 'Miftah / Amad', 'Abi Alif & Tim', 'Admin'] as $pj)
                                <option value="{{ $pj }}"
                                    {{ old('penanggung_jawab', $pondok?->penanggung_jawab) == $pj ? 'selected' : '' }}>
                                    {{ $pj }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control"
                            value="{{ old('no_hp', $pondok?->no_hp) }}" placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Link Google Maps</label>
                        <input type="url" name="google_maps_url" class="form-control"
                            value="{{ old('google_maps_url', $pondok?->google_maps_url) }}" placeholder="https://maps">
                    </div>

                    <div class="alert alert-info" id="jatah-preview" style="display:none">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Jatah:</strong> <span id="preview-kg">0</span> kg =
                        <span id="preview-karung">0</span> karung (1 santri/pengasuh = 1 kg)
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-check-lg me-2"></i>
                        {{ $pondok ? 'Perbarui Data' : 'Simpan Data' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updatePreview() {
            const santri = parseInt(document.querySelector('[name=jumlah_santri]').value) || 0;
            const pengasuh = parseInt(document.querySelector('[name=jumlah_pengasuh]').value) || 0;
            const total = santri + pengasuh;
            const karung = Math.ceil(total / 10);
            document.getElementById('preview-kg').textContent = total;
            document.getElementById('preview-karung').textContent = karung;
            document.getElementById('jatah-preview').style.display = total > 0 ? 'block' : 'none';
        }
        document.querySelector('[name=jumlah_santri]').addEventListener('input', updatePreview);
        document.querySelector('[name=jumlah_pengasuh]').addEventListener('input', updatePreview);
        updatePreview();
    </script>
@endpush
