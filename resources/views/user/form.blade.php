@extends('layouts.app')
@section('title', $user ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">{{ $user ? 'Edit Pengguna' : 'Tambah Pengguna' }}</div>
                <div class="page-subtitle">Kelola akun pengguna sistem</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ $user ? route('user.update', $user) : route('user.store') }}">
                    @csrf
                    @if ($user)
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user?->name) }}" placeholder="Nama pengguna" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user?->email) }}" placeholder="email@contoh.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Role / Hak Akses <span class="text-danger">*</span></label>
                        <select name="role" id="role-select" class="form-select" required onchange="togglePondokField()">
                            <option value="">-- Pilih Role --</option>
                            @foreach ([
            'admin' => '🔑 Administrator – Akses penuh',
            // 'ota' => '💚 OTA / Donatur – Hanya lihat + loading beras',
            'paskas' => '🚚 Paskas – Catat penyaluran',
            // 'pondok' => '🕌 Akun Pondok – Lihat data pondoknya',
        ] as $val => $lbl)
                                <option value="{{ $val }}"
                                    {{ old('role', $user?->role) === $val ? 'selected' : '' }}>
                                    {{ $lbl }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pondok field (only for role=pondok) -->
                    <div class="mb-4" id="pondok-field" style="display:none">
                        <label class="form-label">Hubungkan ke Pondok</label>
                        <select name="pondok_id" class="form-select">
                            <option value="">-- Pilih Pondok --</option>
                            @foreach ($pondoks as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('pondok_id', $user?->pondok_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} – {{ $p->alamat }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Akun pondok hanya bisa melihat data pondok yang dipilih</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user?->phone) }}"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    @if (!$user)
                        <div class="mb-4">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Min. 6 karakter"
                                required minlength="6">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Ulangi password" required>
                        </div>
                    @endif

                    @if ($user)
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ $user->is_active ? 'checked' : '' }} style="width:50px;height:26px">
                                <label class="form-check-label ms-2 fw-bold" for="is_active">Akun Aktif</label>
                            </div>
                        </div>
                    @endif

                    <div class="alert alert-info" style="font-size:0.9rem">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Keterangan Role:</strong><br>
                        {{-- • <strong>OTA</strong>: Hanya bisa lihat data dan catat loading beras masuk<br> --}}
                        • <strong>Paskas</strong>: Bisa catat aktivitas penyaluran beras<br>
                        {{-- • <strong>Pondok</strong>: Hanya bisa lihat data pondoknya sendiri<br> --}}
                        • <strong>Admin</strong>: Akses penuh ke semua fitur
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-check-lg me-2"></i>
                        {{ $user ? 'Perbarui Data' : 'Buat Akun' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePondokField() {
            const role = document.getElementById('role-select').value;
            document.getElementById('pondok-field').style.display = role === 'pondok' ? 'block' : 'none';
        }
        togglePondokField();
    </script>
@endpush
