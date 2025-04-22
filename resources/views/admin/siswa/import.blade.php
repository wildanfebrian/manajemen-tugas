@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Import Data Siswa</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('siswa.index') }}">Siswa</a></li>
        <li class="breadcrumb-item active">Import</li>
    </ol>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-file-import me-1"></i> Import Data Siswa</div>
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

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('siswa.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <div class="alert alert-info">
                                <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Petunjuk Import</h5>
                                <p>Silakan ikuti langkah-langkah berikut untuk mengimpor data siswa:</p>
                                <ol>
                                    <li>Download template file Excel <a href="{{ route('siswa.import.template') }}" class="alert-link">di sini</a>.</li>
                                    <li>Isi data siswa sesuai format template.</li>
                                    <li>Pastikan NIS tidak ada yang duplikat.</li>
                                    <li>Pilih kelas yang sesuai pada formulir di bawah.</li>
                                    <li>Upload file Excel yang sudah diisi.</li>
                                    <li>Klik tombol "Import Data".</li>
                                </ol>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id" name="kelas_id">
                                <option value="">-- Pilih Kelas (Opsional) --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->namakelas }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Jika dipilih, semua siswa yang diimpor akan ditempatkan pada kelas ini. Jika tidak, gunakan kolom kelas_id pada file Excel.</div>
                            @error('kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">File Excel</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".xls,.xlsx,.csv" required>
                            <div class="form-text">Format file yang diterima: .xlsx, .xls, .csv</div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="header_row" name="header_row" value="1" checked>
                            <label class="form-check-label" for="header_row">File memiliki baris header</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-import me-1"></i> Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Riwayat Import -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-history me-1"></i> Riwayat Import Terakhir
                </div>
                <div class="card-body">
                    @if(isset($importHistory) && $importHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah Data</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($importHistory as $history)
                                        <tr>
                                            <td>{{ $history->created_at->format('d M Y, H:i') }}</td>
                                            <td>{{ $history->total_records }}</td>
                                            <td>
                                                @if($history->status == 'success')
                                                    <span class="badge bg-success">Berhasil</span>
                                                @elseif($history->status == 'partial')
                                                    <span class="badge bg-warning">Sebagian Berhasil</span>
                                                @else
                                                    <span class="badge bg-danger">Gagal</span>
                                                @endif
                                            </td>
                                            <td>{{ $history->notes }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-1"></i> Belum ada riwayat import.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 