@extends('layouts.app')
@section('title', 'Loading Beras')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('stok.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">Loading Beras</div>
                <div class="page-subtitle">Catat penerimaan beras masuk</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('stok.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Tanggal Penerimaan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control"
                            value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Jumlah Karung <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_karung" id="jumlah_karung" class="form-control"
                            value="{{ old('jumlah_karung') }}" placeholder="Contoh: 50" min="1" required>
                        <div class="form-text mt-2">
                            <span id="kg-preview" class="badge bg-info fs-6">= 0 kg</span>
                            <span class="ms-2 text-muted">(1 karung = 10 kg)</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Sumber / Nama Donatur <span class="text-danger">*</span></label>
                        <input type="text" name="sumber" class="form-control" value="{{ old('sumber') }}"
                            placeholder="Contoh: H. Ahmad - Balikpapan" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-check-lg me-2"></i>Simpan Data Loading
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('jumlah_karung').addEventListener('input', function() {
            const val = parseInt(this.value) || 0;
            document.getElementById('kg-preview').textContent = '= ' + (val * 10) + ' kg';
        });
    </script>
@endpush
