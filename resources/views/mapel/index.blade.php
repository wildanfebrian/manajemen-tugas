@extends('back.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Mata Pelajaran</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mapelModal">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Mapel
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Materi</th>
                                    <th width="45%">Deskripsi</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mapel as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->materi }}</td>
                                        <td>{{ $item->deskripsi }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group" aria-label="Action buttons">
                                                <button type="button" 
                                                        class="btn btn-warning btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editModal{{ $item->id }}"
                                                        title="Edit Mapel">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.mapel.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm"
                                                            title="Hapus Mapel">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-edit me-2"></i>Edit Mapel
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.mapel.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="materi" class="form-label">Materi</label>
                                                            <input type="text" 
                                                                   class="form-control @error('materi') is-invalid @enderror" 
                                                                   id="materi" 
                                                                   name="materi" 
                                                                   value="{{ old('materi', $item->materi) }}" 
                                                                   required>
                                                            @error('materi')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                                      id="deskripsi" 
                                                                      name="deskripsi" 
                                                                      rows="3" 
                                                                      required>{{ old('deskripsi', $item->deskripsi) }}</textarea>
                                                            @error('deskripsi')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i>Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save me-1"></i>Simpan
                                                        </button>
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
<div class="modal fade" id="mapelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Mapel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.mapel.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="materi" class="form-label">Materi</label>
                        <input type="text" 
                               class="form-control @error('materi') is-invalid @enderror" 
                               id="materi" 
                               name="materi" 
                               value="{{ old('materi') }}" 
                               required>
                        @error('materi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3" 
                                  required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Auto-hide alerts after 5 seconds
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 5000);
</script>
@endpush

@endsection