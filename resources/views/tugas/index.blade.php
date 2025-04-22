@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Tugas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Tugas</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-table me-1"></i> Data Tugas</div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTugasModal">
                <i class="fas fa-plus"></i> Tambah Tugas
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
                <form action="{{ route('admin.tugas.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan mata pelajaran..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTugas">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Mata Pelajaran</th>
                            <th width="20%">Deskripsi</th>
                            <th width="20%">Kelas</th>
                            <th width="20%">File</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tugas as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->mapel->materi }}</td>
                                <td>{{ $item->mapel->deskripsi }}</td>
                                <td>
                                    @if($item->kelas->count() > 0)
                                        <ul class="list-unstyled mb-0">
                                            @foreach($item->kelas as $k)
                                                <li><span class="badge bg-info">{{ $k->namakelas }}</span></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Tidak ada kelas</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->filetugas)
                                        <a href="{{ route('admin.tugas.download', $item->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada file</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTugasModal{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.tugas.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editTugasModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Tugas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.tugas.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="mapel_id{{ $item->id }}" class="form-label">Mata Pelajaran</label>
                                                    <select class="form-select @error('mapel_id') is-invalid @enderror" id="mapel_id{{ $item->id }}" name="mapel_id" required>
                                                        @foreach($mapel as $m)
                                                            <option value="{{ $m->id }}" {{ $item->mapel_id == $m->id ? 'selected' : '' }}>
                                                                {{ $m->materi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('mapel_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="kelas_id{{ $item->id }}" class="form-label">Kelas</label>
                                                    <select class="form-select select2 @error('kelas_id') is-invalid @enderror" id="kelas_id{{ $item->id }}" name="kelas_id[]" multiple required>
                                                        @foreach($kelas as $k)
                                                            <option value="{{ $k->id }}" {{ $item->kelas->contains($k->id) ? 'selected' : '' }}>
                                                                {{ $k->namakelas }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('kelas_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="filetugas{{ $item->id }}" class="form-label">File Tugas</label>
                                                    <input type="file" class="form-control @error('filetugas') is-invalid @enderror" id="filetugas{{ $item->id }}" name="filetugas">
                                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file</small>
                                                    @error('filetugas')
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
                                <td colspan="5" class="text-center">Tidak ada data tugas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addTugasModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tugas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.tugas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="mapel_id">Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_id" class="form-control" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->id }}">{{ $m->materi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kelas_id">Kelas</label>
                        <select name="kelas_id[]" id="kelas_id" class="form-control select2" multiple required>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->namakelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="filetugas">File Tugas</label>
                        <input type="file" name="filetugas" id="filetugas" class="form-control" required>
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
        $('#dataTugas').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            info: true,
        });

        $('.select2').select2({
            placeholder: 'Pilih Kelas',
            allowClear: true,
            dropdownParent: $('.modal')
        });
        
        // Confirm delete
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus tugas ini?')) {
                this.submit();
            }
        });
    });
</script>
@endpush

@endsection 