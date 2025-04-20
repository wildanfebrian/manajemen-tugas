@extends('back.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Kelas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('kelas.index') }}">Kelas</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="namakelas" class="form-label">Nama Kelas</label>
                    <input type="text" class="form-control @error('namakelas') is-invalid @enderror" 
                           id="namakelas" name="namakelas" value="{{ old('namakelas', $kelas->namakelas) }}" required>
                    @error('namakelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="peryataan" class="form-label">Peryataan</label>
                    <textarea class="form-control @error('peryataan') is-invalid @enderror" 
                              id="peryataan" name="peryataan" rows="3" required>{{ old('peryataan', $kelas->peryataan) }}</textarea>
                    @error('peryataan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 