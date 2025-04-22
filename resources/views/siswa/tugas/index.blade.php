@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Tugas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Tugas</li>
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
            <i class="fas fa-tasks me-1"></i> Daftar Tugas untuk Kelas {{ $siswa->kelas->namakelas }}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Mata Pelajaran</th>
                            <th width="25%">Deskripsi</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tugas as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->mapel->materi }}</td>
                                <td>{{ $item->mapel->deskripsi }}</td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $submission = $item->nilai->where('siswa_id', $siswa->id)->first();
                                    @endphp
                                    
                                    @if(!$submission)
                                        <span class="badge bg-danger">Belum Dikumpulkan</span>
                                    @elseif($submission->status == 'submitted')
                                        <span class="badge bg-warning">Sudah Dikumpulkan</span>
                                    @elseif($submission->status == 'graded')
                                        <span class="badge bg-success">Sudah Dinilai ({{ $submission->hasil }})</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('siswa.tugas.show', $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('siswa.tugas.download', $item->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada tugas untuk kelas Anda</td>
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
        $('#dataTable').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            info: true,
        });
    });
</script>
@endpush

@endsection 