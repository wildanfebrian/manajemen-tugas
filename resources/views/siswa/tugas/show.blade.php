@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Tugas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('siswa.tugas.index') }}">Tugas</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
    
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
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i> Informasi Tugas
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Mata Pelajaran</div>
                        <div class="col-md-8">{{ $tugas->mapel->materi }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Deskripsi</div>
                        <div class="col-md-8">{{ $tugas->mapel->deskripsi }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Kelas</div>
                        <div class="col-md-8">
                            @foreach($tugas->kelas as $kelas)
                                <span class="badge bg-info">{{ $kelas->namakelas }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Tanggal Diberikan</div>
                        <div class="col-md-8">{{ $tugas->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">File Tugas</div>
                        <div class="col-md-8">
                            <a href="{{ route('siswa.tugas.download', $tugas->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-download"></i> Download File Tugas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-upload me-1"></i> Pengumpulan Tugas
                </div>
                <div class="card-body">
                    @if($submission)
                        <div class="alert alert-success">
                            <h5 class="alert-heading"><i class="fas fa-check-circle"></i> Tugas Sudah Dikumpulkan</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Tanggal Pengumpulan</div>
                                <div class="col-md-8">{{ date('d M Y H:i', $submission->tanggal) }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Status</div>
                                <div class="col-md-8">
                                    @if($submission->status == 'submitted')
                                        <span class="badge bg-warning">Sudah Dikumpulkan (Menunggu Penilaian)</span>
                                    @elseif($submission->status == 'graded')
                                        <span class="badge bg-success">Sudah Dinilai</span>
                                    @endif
                                </div>
                            </div>
                            @if($submission->status == 'graded')
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Nilai</div>
                                <div class="col-md-8">
                                    <h4><span class="badge bg-primary">{{ is_numeric($submission->hasil) ? $submission->hasil : 'N/A' }}</span></h4>
                                </div>
                            </div>
                            @endif
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">File yang Dikumpulkan</div>
                                <div class="col-md-8">
                                    @if($submission->hasil && !is_numeric($submission->hasil))
                                        <a href="{{ route('siswa.submission.download', $submission->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-download"></i> Download File
                                        </a>
                                    @elseif(is_numeric($submission->hasil))
                                        <span class="text-muted">Penilaian numerik</span>
                                    @else
                                        <span class="text-muted">Tidak ada file</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('siswa.tugas.submit', $tugas->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="hasil" class="form-label">Upload File Hasil Tugas</label>
                                <input type="file" name="hasil" id="hasil" class="form-control @error('hasil') is-invalid @enderror" required>
                                <div class="form-text">Maksimal ukuran file 10MB</div>
                                @error('hasil')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane"></i> Kumpulkan Tugas
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 