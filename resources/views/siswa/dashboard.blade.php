@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard Siswa</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
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
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user me-1"></i> Informasi Siswa
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Nama</div>
                <div class="col-md-9">{{ $siswa->nama }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">NIS</div>
                <div class="col-md-9">{{ $siswa->nis }}</div>
            </div>
            <div class="row">
                <div class="col-md-3 fw-bold">Kelas</div>
                <div class="col-md-9">{{ $siswa->kelas->namakelas }}</div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-tasks me-1"></i> Tugas Terbaru</div>
            <a href="{{ route('siswa.tugas.index') }}" class="btn btn-primary btn-sm">
                Lihat Semua Tugas
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="30%">Mata Pelajaran</th>
                            <th width="35%">Deskripsi</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tugas->take(5) as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->mapel->materi }}</td>
                                <td>{{ $item->mapel->deskripsi }}</td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('siswa.tugas.show', $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada tugas untuk kelas Anda</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 