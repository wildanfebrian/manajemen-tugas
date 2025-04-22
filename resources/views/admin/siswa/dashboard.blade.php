@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard Siswa</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Siswa</li>
    </ol>
    
    <div class="row">
        <!-- Kartu Total Siswa -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Siswa</div>
                            <div class="display-6">{{ $totalSiswa }}</div>
                        </div>
                        <i class="fas fa-users fa-3x text-white-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('siswa.index') }}">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <!-- Kartu Total Kelas -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Kelas</div>
                            <div class="display-6">{{ $totalKelas }}</div>
                        </div>
                        <i class="fas fa-school fa-3x text-white-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.kelas.index') }}">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <!-- Kartu Siswa Terbaru -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-white-50">Siswa Terbaru</div>
                            <div class="display-6">{{ $newSiswa }}</div>
                        </div>
                        <i class="fas fa-user-plus fa-3x text-white-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('siswa.index', ['sort' => 'newest']) }}">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <!-- Kartu Tindakan Cepat -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-white-50">Tindakan Cepat</div>
                            <div class="display-6">Menu</div>
                        </div>
                        <i class="fas fa-tools fa-3x text-white-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <button type="button" class="small text-white border-0 bg-transparent p-0" data-bs-toggle="modal" data-bs-target="#quickActionsModal">
                        Akses Tindakan
                    </button>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-8">
            <!-- Grafik Siswa per Kelas -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Jumlah Siswa per Kelas
                </div>
                <div class="card-body">
                    <canvas id="siswaPerKelasChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <!-- Siswa Terbaru -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-clock me-1"></i>
                    Siswa Terbaru
                </div>
                <div class="card-body">
                    @if(count($recentSiswa) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentSiswa as $siswa)
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $siswa->nama }}</h6>
                                            <small class="text-muted">
                                                <span class="badge bg-secondary">{{ $siswa->nis }}</span>
                                                {{ $siswa->kelas->namakelas }}
                                            </small>
                                        </div>
                                        <a href="{{ route('siswa.show', $siswa->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('siswa.index') }}" class="btn btn-outline-primary btn-sm">
                                Lihat Semua Siswa
                            </a>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            Belum ada data siswa.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Distribusi Siswa per Kelas
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="distribusiSiswaTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Laki-laki</th>
                                    <th>Perempuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kelasDistribution as $item)
                                    <tr>
                                        <td>{{ $item->namakelas }}</td>
                                        <td>{{ $item->total_siswa }}</td>
                                        <td>{{ $item->siswa_laki ?? 'N/A' }}</td>
                                        <td>{{ $item->siswa_perempuan ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('siswa.by.kelas', $item->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-users"></i> Lihat Siswa
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tindakan Cepat -->
<div class="modal fade" id="quickActionsModal" tabindex="-1" aria-labelledby="quickActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickActionsModalLabel">Tindakan Cepat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <a href="{{ route('siswa.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-list fa-fw"></i> Daftar Semua Siswa
                    </a>
                    <button type="button" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addSiswaModal" data-bs-dismiss="modal">
                        <i class="fas fa-user-plus fa-fw"></i> Tambah Siswa Baru
                    </button>
                    <a href="{{ route('siswa.import') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-import fa-fw"></i> Import Data Siswa
                    </a>
                    <a href="{{ route('siswa.export') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-export fa-fw"></i> Export Data Siswa
                    </a>
                    <a href="{{ route('admin.kelas.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-school fa-fw"></i> Kelola Kelas
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div class="modal fade" id="addSiswaModal" tabindex="-1" aria-labelledby="addSiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSiswaModalLabel">Tambah Siswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('siswa.store') }}" method="POST">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik distribusi siswa per kelas
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('siswaPerKelasChart').getContext('2d');
        
        // Data dari controller
        const kelasLabels = {!! json_encode($kelasDistribution->pluck('namakelas')) !!};
        const siswaCounts = {!! json_encode($kelasDistribution->pluck('total_siswa')) !!};
        
        const siswaPerKelasChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: kelasLabels,
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: siswaCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection 