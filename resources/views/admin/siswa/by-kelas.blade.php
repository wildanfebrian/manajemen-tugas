@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Siswa Kelas {{ $kelas->namakelas }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('siswa.index') }}">Siswa</a></li>
        <li class="breadcrumb-item active">Kelas {{ $kelas->namakelas }}</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-users me-1"></i> Daftar Siswa Kelas {{ $kelas->namakelas }}</div>
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-body bg-light">
                    <h5 class="card-title">Informasi Kelas</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Kelas:</strong> {{ $kelas->namakelas }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Peryataan:</strong> {{ $kelas->peryataan }}</p>
                        </div>
                    </div>
                    <p><strong>Total Siswa:</strong> {{ $siswa->count() }} orang</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataSiswaByKelas">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">NIS</th>
                            <th width="40%">Nama Siswa</th>
                            <th width="15%">Nilai Rata-Rata</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->nis }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>
                                    @if($item->nilai->count() > 0)
                                        {{ number_format($item->nilai->avg('hasil'), 1) }}
                                    @else
                                        <span class="badge bg-secondary">Belum ada nilai</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('siswa.show', $item->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSiswaModal{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSiswaModal{{ $item->id }}" tabindex="-1" aria-labelledby="editSiswaModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSiswaModalLabel{{ $item->id }}">Edit Siswa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('siswa.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="kelas_id{{ $item->id }}" class="form-label">Kelas</label>
                                                    <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id{{ $item->id }}" name="kelas_id" required>
                                                        @foreach($allKelas as $k)
                                                            <option value="{{ $k->id }}" {{ $item->kelas_id == $k->id ? 'selected' : '' }}>
                                                                {{ $k->namakelas }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('kelas_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nama{{ $item->id }}" class="form-label">Nama Siswa</label>
                                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama{{ $item->id }}" name="nama" value="{{ old('nama', $item->nama) }}" required>
                                                    @error('nama')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nis{{ $item->id }}" class="form-label">NIS</label>
                                                    <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis{{ $item->id }}" name="nis" value="{{ old('nis', $item->nis) }}" required>
                                                    @error('nis')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password{{ $item->id }}" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password{{ $item->id }}" name="password">
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
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada siswa di kelas ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataSiswaByKelas').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            info: true,
        });
    });
</script>
@endpush

@endsection 