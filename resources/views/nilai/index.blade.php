@extends('back.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Nilai</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNilaiModal">
                        Tambah Nilai
                    </button>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <form action="{{ route('nilai.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan nama siswa atau mata pelajaran..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary">Cari</button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Siswa</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Hasil</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nilai as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->siswa->nama }}</td>
                                        <td>{{ $item->tugas->mapel->materi }}</td>
                                        <td>{{ $item->hasil }}</td>
                                        <td>{{ $item->tanggal }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editNilaiModal{{ $item->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('nilai.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editNilaiModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Nilai</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('nilai.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="siswa_id" class="form-label">Siswa</label>
                                                            <select class="form-select" id="siswa_id" name="siswa_id" required>
                                                                @foreach($siswa as $s)
                                                                    <option value="{{ $s->id }}" {{ $item->siswa_id == $s->id ? 'selected' : '' }}>
                                                                        {{ $s->nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tugas_id" class="form-label">Tugas</label>
                                                            <select class="form-select" id="tugas_id" name="tugas_id" required>
                                                                @foreach($tugas as $t)
                                                                    <option value="{{ $t->id }}" {{ $item->tugas_id == $t->id ? 'selected' : '' }}>
                                                                        {{ $t->mapel->materi }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="hasil" class="form-label">Hasil</label>
                                                            <input type="number" class="form-control" id="hasil" name="hasil" value="{{ $item->hasil }}" min="0" max="100" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal" class="form-label">Tanggal</label>
                                                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $item->tanggal }}" required>
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
<div class="modal fade" id="addNilaiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Nilai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="siswa_id" class="form-label">Siswa</label>
                        <select class="form-select" id="siswa_id" name="siswa_id" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($siswa as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tugas_id" class="form-label">Tugas</label>
                        <select class="form-select" id="tugas_id" name="tugas_id" required>
                            <option value="">Pilih Tugas</option>
                            @foreach($tugas as $t)
                                <option value="{{ $t->id }}">{{ $t->mapel->materi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="hasil" class="form-label">Hasil</label>
                        <input type="number" class="form-control" id="hasil" name="hasil" min="0" max="100" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
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