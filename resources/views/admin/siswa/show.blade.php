@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Siswa</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('siswa.index') }}">Siswa</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-user me-1"></i> Informasi Siswa</div>
                        <div>
                            <a href="{{ route('siswa.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSiswaModal">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12 text-center mb-4">
                            <div class="avatar-container">
                                <div class="avatar-circle">
                                    <span class="avatar-initials">{{ substr($siswa->nama, 0, 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 35%">NIS</th>
                                    <td>{{ $siswa->nis }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td>{{ $siswa->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td>{{ $siswa->kelas->namakelas }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Terdaftar</th>
                                    <td>{{ $siswa->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diperbarui</th>
                                    <td>{{ $siswa->updated_at->format('d M Y, H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Nilai Siswa Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-line me-1"></i> Nilai Siswa
                </div>
                <div class="card-body">
                    @if($siswa->nilai->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tugas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Nilai</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($siswa->nilai as $key => $nilai)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $nilai->tugas->nama_tugas ?? 'Tugas ' . $nilai->tugas_id }}</td>
                                            <td>{{ $nilai->tugas->mapel->materi ?? 'Tidak tersedia' }}</td>
                                            <td>{{ $nilai->hasil }}</td>
                                            <td>{{ $nilai->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-1"></i> Siswa ini belum memiliki data nilai.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editSiswaModal" tabindex="-1" aria-labelledby="editSiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSiswaModalLabel">Edit Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id" name="kelas_id" required>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->namakelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $siswa->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis" value="{{ old('nis', $siswa->nis) }}" required>
                        @error('nis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .avatar-container {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }
    .avatar-circle {
        width: 100px;
        height: 100px;
        background-color: #4e73df;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 40px;
        font-weight: bold;
    }
    .avatar-initials {
        text-transform: uppercase;
    }
</style>

@endsection 