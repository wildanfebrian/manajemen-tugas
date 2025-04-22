@extends('back.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Kelas</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKelasModal">
                        Tambah Kelas
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
                        <form action="{{ route('admin.kelas.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan nama kelas atau peryataan..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary">Cari</button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kelas as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->namakelas }}</td>
                                        <td>{{ $item->peryataan }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editKelasModal{{ $item->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editKelasModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Kelas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.kelas.update', $item->id) }}" method="POST" id="editForm{{ $item->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="namakelas{{ $item->id }}" class="form-label">Nama Kelas</label>
                                                            <input type="text" class="form-control @error('namakelas') is-invalid @enderror" 
                                                                   id="namakelas{{ $item->id }}" name="namakelas" 
                                                                   value="{{ old('namakelas', $item->namakelas) }}" required>
                                                            @error('namakelas')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="peryataan{{ $item->id }}" class="form-label">Deskripsi</label>
                                                            <input type="text" class="form-control @error('peryataan') is-invalid @enderror" 
                                                                   id="peryataan{{ $item->id }}" name="peryataan" 
                                                                   value="{{ old('peryataan', $item->peryataan) }}" required>
                                                            @error('peryataan')
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addKelasModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="namakelas" class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control @error('namakelas') is-invalid @enderror" 
                               id="namakelas" name="namakelas" 
                               value="{{ old('namakelas') }}" required>
                        @error('namakelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="peryataan" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control @error('peryataan') is-invalid @enderror" 
                               id="peryataan" name="peryataan" 
                               value="{{ old('peryataan') }}" required>
                        @error('peryataan')
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
@endsection 