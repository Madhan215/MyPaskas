@extends('layouts.app')
@section('title', 'Edit Profil')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('profil.show') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">Edit Profil</div>
                <div class="page-subtitle">Perbarui data diri Anda</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Foto Profil -->
                    <div class="text-center mb-4">
                        <img src="{{ $user->foto_profil ? asset($user->foto_profil) : asset('uploads/profil/default.png') }}"
                            id="preview-profil"
                            style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:4px solid #dcfce7;margin-bottom:12px">
                        <div>
                            <button type="button" class="btn btn-outline-success btn-sm"
                                onclick="document.getElementById('foto-input').click()">
                                <i class="bi bi-camera me-1"></i>Ganti Foto
                            </button>
                            <input type="file" id="foto-input" name="foto_profil" accept="image/*" style="display:none"
                                onchange="previewFoto(this)">
                        </div>
                        <div class="form-text mt-1">Format: JPG/PNG</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"
                            placeholder="08xxxxxxxxxx">
                    </div>


                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('preview-profil').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
