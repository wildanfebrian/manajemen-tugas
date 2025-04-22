@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Siswa</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Siswa</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-table me-1"></i> Data Siswa</div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSiswaModal">
                <i class="fas fa-plus"></i> Tambah Siswa
            </button>
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

            <div class="mb-3">
                <form action="{{ route('admin.siswa.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan nama, NIS, atau kelas..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataSiswa">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">NIS</th>
                            <th width="25%">Nama</th>
                            <th width="20%">Kelas</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->nis }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->kelas->namakelas }}</td>
                                <td>
                                    <a href="{{ route('admin.siswa.show', $item->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSiswaModal{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.siswa.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')">
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
                                        <form action="{{ route('admin.siswa.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="kelas_id{{ $item->id }}" class="form-label">Kelas</label>
                                                    <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id{{ $item->id }}" name="kelas_id" required>
                                                        @foreach($kelas as $k)
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
                                <td colspan="5" class="text-center">Tidak ada data siswa</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addSiswaModal" tabindex="-1" aria-labelledby="addSiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSiswaModalLabel">Tambah Siswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.siswa.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id" name="kelas_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
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
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis" value="{{ old('nis') }}" required>
                        @error('nis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataSiswa').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            info: true,
        });
    });
</script>
@endpush

@endsection 