@extends('layouts.app')
@section('title', 'Data Pondok')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div class="page-title">Data Pondok</div>
                <div class="page-subtitle">{{ $pondoks->count() }} pondok/yayasan terdaftar</div>
            </div>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('pondok.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg me-1"></i> Tambah
                </a>
            @endif
        </div>

        @php
            $kelompoks = $pondoks->groupBy('penanggung_jawab');
        @endphp

        <form method="GET" class="mb-3">

            <div class="input-group">

                <span class="input-group-text bg-white">
                    <i class="bi bi-search"></i>
                </span>

                <input type="text" name="q" id="search-input" class="form-control"
                    placeholder="Cari pondok, alamat, atau pj..." value="{{ request('q') }}" autocomplete="off">

                @if (request('q'))
                    <a href="{{ route('pondok.index') }}"
                        class="btn btn-outline-secondary d-flex align-items-center justify-content-center"
                        style="width:42px">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif

            </div>

        </form>

        @foreach ($kelompoks as $kelompok => $list)
            <div class="card mb-3">
                <div class="card-header" style="background:#f0fdf4;color:#14532d">
                    <i class="bi bi-people-fill me-2"></i>{{ $kelompok ?? 'Tanpa Kelompok' }}
                    <span class="badge bg-success ms-2">{{ $list->count() }} tempat</span>
                    <span class="badge bg-warning text-dark ms-1">{{ $list->sum('jumlah_santri') }} santri</span>
                </div>

                <div class="card-body p-0">

                    @foreach ($list as $pondok)
                        <div class="d-flex align-items-center p-3 border-bottom gap-3">
                            <div
                                style="width:48px;height:48px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0">
                                🕌
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $pondok->nama }} @if ($pondok->google_maps_url)
                                        <a href="{{ $pondok->google_maps_url }}" target="_blank"
                                            class="btn btn-sm btn-outline-success py-0 px-2">
                                            <i class="bi bi-link-45deg"></i> Maps
                                        </a>
                                    @endif
                                </div>
                                <div style="font-size:0.85rem;color:#6b7280">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $pondok->alamat }}
                                </div>
                                <div class="mt-1 d-flex gap-2 flex-wrap">
                                    <span class="badge bg-success">{{ $pondok->jumlah_santri }} Santri</span>
                                    <span class="badge bg-info">{{ $pondok->jatah_kg }} Kg</span>
                                    <span class="badge bg-warning text-dark">{{ $pondok->jatah_karung }} Krg</span>
                                </div>
                            </div>
                            @if (Auth::user()->isAdmin())
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{ route('pondok.edit', $pondok) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form id="del-{{ $pondok->id }}" method="POST"
                                        action="{{ route('pondok.destroy', $pondok) }}">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button class="btn btn-sm btn-outline-danger btn-delete-confirm"
                                        data-form="del-{{ $pondok->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="card-footer bg-white text-end">
                    <small class="text-muted fw-bold">
                        Total: {{ $list->sum('jumlah_santri') }} santri ·
                        {{ $list->sum('jatah_kg') }} kg ·
                        {{ $list->sum('jatah_karung') }} karung
                    </small>
                </div>
            </div>
        @endforeach

        <!-- Grand Total -->
        <div class="card" style="background:linear-gradient(135deg,#14532d,#166534);color:#fff">
            <div class="card-body">
                <div class="fw-bold mb-2 fs-5">📊 Grand Total</div>
                <div class="row text-center g-2">
                    <div class="col-4">
                        <div style="font-size:1.8rem;font-weight:800">{{ $pondoks->sum('jumlah_santri') }}</div>
                        <div style="opacity:0.8;font-size:0.85rem">Total Santri</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.8rem;font-weight:800">{{ $pondoks->sum('jatah_kg') }}</div>
                        <div style="opacity:0.8;font-size:0.85rem">Total KG</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.8rem;font-weight:800">{{ $pondoks->sum('jatah_karung') }}</div>
                        <div style="opacity:0.8;font-size:0.85rem">Total Karung</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const searchInput = document.getElementById('search-input');

        // fokus otomatis
        window.addEventListener('load', () => {

            searchInput.focus();

            searchInput.setSelectionRange(
                searchInput.value.length,
                searchInput.value.length
            );
        });

        // submit saat enter
        searchInput.addEventListener('keypress', function(e) {

            if (e.key === 'Enter') {

                e.preventDefault();

                this.form.submit();
            }
        });
    </script>
@endsection
