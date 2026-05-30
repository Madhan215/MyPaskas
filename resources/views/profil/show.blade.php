@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
    <div class="py-3">
        <div class="page-title mb-1">Profil Saya</div>
        <div class="page-subtitle mb-4">Informasi akun dan pengaturan</div>

        <!-- Profil Card -->
        <div class="card mb-3">
            <div class="card-body p-4 text-center">
                <img src="{{ $user->foto_profil ? asset($user->foto_profil) : asset('uploads/profil/default.png') }}"
                    style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:4px solid #dcfce7;margin-bottom:12px">
                <div class="fw-bold fs-4">{{ $user->name }}</div>
                <div class="mb-2">
                    <span class="badge bg-{{ $user->role_badge }} fs-6">{{ $user->role_label }}</span>
                </div>
                <div class="text-muted">{{ $user->email }}</div>
                @if ($user->phone)
                    <div class="text-muted mt-1"><i class="bi bi-phone me-1"></i>{{ $user->phone }}</div>
                @endif
                @if ($user->isPondok() && $user->pondok)
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="bi bi-building me-2"></i>Terhubung ke: <strong>{{ $user->pondok->nama }}</strong>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white text-center">
                <a href="{{ route('profil.edit') }}" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-pencil me-2"></i>Edit Profil
                </a>
            </div>
        </div>

        <!-- Ganti Password -->
        <div class="card mb-3">
            <div class="card-header" style="background:#f0fdf4;color:#14532d">
                <i class="bi bi-key-fill me-2"></i>Ganti Password
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profil.password') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="password_lama"
                            class="form-control @error('password_lama') is-invalid @enderror"
                            placeholder="Masukkan password lama" required>
                        @error('password_lama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Min. 6 karakter" required minlength="6">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password baru" required>
                    </div>
                    <button type="submit" class="btn btn-warning fw-bold w-100">
                        <i class="bi bi-shield-lock me-2"></i>Ganti Password
                    </button>
                </form>
            </div>
        </div>




    </div>
@endsection
