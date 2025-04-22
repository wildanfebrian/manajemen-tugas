@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Export Data Siswa</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('siswa.index') }}">Siswa</a></li>
        <li class="breadcrumb-item active">Export</li>
    </ol>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-file-export me-1"></i> Export Data Siswa</div>
                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('siswa.export.process') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="export_type" class="form-label">Format Export</label>
                            <select class="form-select" id="export_type" name="export_type" required>
                                <option value="xlsx">Excel (.xlsx)</option>
                                <option value="csv">CSV (.csv)</option>
                                <option value="pdf">PDF (.pdf)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Filter Kelas</label>
                            <select class="form-select" id="kelas_id" name="kelas_id">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->namakelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Kolom yang Diekspor</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="nis" id="col_nis" name="columns[]" checked>
                                        <label class="form-check-label" for="col_nis">
                                            NIS
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="nama" id="col_nama" name="columns[]" checked>
                                        <label class="form-check-label" for="col_nama">
                                            Nama
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="kelas" id="col_kelas" name="columns[]" checked>
                                        <label class="form-check-label" for="col_kelas">
                                            Kelas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="created_at" id="col_created_at" name="columns[]">
                                        <label class="form-check-label" for="col_created_at">
                                            Tanggal Terdaftar
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="nilai_avg" id="col_nilai_avg" name="columns[]">
                                        <label class="form-check-label" for="col_nilai_avg">
                                            Rata-rata Nilai
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Centang kolom yang ingin disertakan dalam file export.
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-export me-1"></i> Export Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Ekspor Cepat -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-bolt me-1"></i> Ekspor Cepat
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('siswa.export.all', ['format' => 'xlsx']) }}" class="btn btn-success w-100">
                                <i class="fas fa-file-excel me-1"></i> Semua Siswa (Excel)
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('siswa.export.all', ['format' => 'csv']) }}" class="btn btn-secondary w-100">
                                <i class="fas fa-file-csv me-1"></i> Semua Siswa (CSV)
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('siswa.export.all', ['format' => 'pdf']) }}" class="btn btn-danger w-100">
                                <i class="fas fa-file-pdf me-1"></i> Semua Siswa (PDF)
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <p class="fw-bold mb-2">Ekspor per Kelas:</p>
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            @foreach($kelas as $k)
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $k->namakelas }}</h5>
                                            <p class="card-text text-muted">{{ $k->peryataan }}</p>
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('siswa.export.kelas', ['kelas' => $k->id, 'format' => 'xlsx']) }}" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-file-excel"></i> Excel
                                                </a>
                                                <a href="{{ route('siswa.export.kelas', ['kelas' => $k->id, 'format' => 'csv']) }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-file-csv"></i> CSV
                                                </a>
                                                <a href="{{ route('siswa.export.kelas', ['kelas' => $k->id, 'format' => 'pdf']) }}" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 