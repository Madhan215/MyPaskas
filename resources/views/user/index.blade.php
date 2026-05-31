@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div class="page-title">Manajemen User</div>
                <div class="page-subtitle">{{ $users->count() }} akun terdaftar</div>
            </div>
            <a href="{{ route('user.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus me-1"></i> Tambah
            </a>
        </div>

        @foreach ($users->groupBy('role') as $role => $list)
            <div class="card mb-3">
                <div class="card-header" style="background:#f0fdf4;color:#14532d">
                    <i class="bi bi-people-fill me-2"></i>
                    @php
                        $roleLabels = [
                            'admin' => 'Administrator',
                            'paskas' => 'Paskas',
                        ];
                    @endphp
                    {{ $roleLabels[$role] ?? ucfirst($role) }}
                    <span class="badge bg-secondary ms-2">{{ $list->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @foreach ($list as $u)
                        <div class="d-flex align-items-center p-3 border-bottom gap-3">
                            <img src="{{ !empty($u->foto_profil) ? asset('storage/' . $u->foto_profil) : asset('default.png') }}"
                                style="width:48px;height:48px;border-radius:50%;object-fit:cover;flex-shrink:0">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $u->name }}</div>
                                <div style="font-size:0.82rem;color:#6b7280">{{ $u->email }}</div>
                                <div class="mt-1 d-flex gap-2 flex-wrap">
                                    @php
                                        $roleColors = [
                                            'admin' => 'primary',
                                            'paskas' => 'success',
                                        ];
                                    @endphp

                                    <span class="badge bg-{{ $roleColors[$u->role] ?? 'secondary' }}">
                                        {{ $roleLabels[$u->role] ?? ucfirst($u->role) }}
                                    </span>
                                    @if (!$u->is_active)
                                        <span class="badge bg-danger">Non-aktif</span>
                                    @endif
                                    {{-- @if ($u->pondok)
                                        <span class="badge bg-warning text-dark">{{ $u->pondok->nama }}</span>
                                    @endif --}}
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('user.edit', $u) }}">
                                            <i class="bi bi-pencil me-2"></i>Edit Data
                                        </a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#resetModal{{ $u->id }}">
                                            <i class="bi bi-key me-2"></i>Reset Password
                                        </a></li>
                                    <li>
                                        <form method="POST" action="{{ route('user.toggle-active', $u) }}">
                                            @csrf
                                            <button class="dropdown-item" type="submit">
                                                <i
                                                    class="bi bi-{{ $u->is_active ? 'pause-circle' : 'play-circle' }} me-2"></i>
                                                {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </li>
                                    @if ($u->id !== auth()->id())
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form id="del-user-{{ $u->id }}" method="POST"
                                                action="{{ route('user.destroy', $u) }}">
                                                @csrf @method('DELETE')
                                            </form>
                                            <button class="dropdown-item text-danger btn-delete-confirm"
                                                data-form="del-user-{{ $u->id }}">
                                                <i class="bi bi-trash me-2"></i>Hapus Akun
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <!-- Reset Password Modal -->
                        <div class="modal fade" id="resetModal{{ $u->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold">Reset Password – {{ $u->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('user.reset-password', $u) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Password Baru</label>
                                                <input type="password" name="password" class="form-control"
                                                    placeholder="Min. 6 karakter" required minlength="6">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Konfirmasi Password</label>
                                                <input type="password" name="password_confirmation" class="form-control"
                                                    placeholder="Ulangi password" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success">Simpan Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
