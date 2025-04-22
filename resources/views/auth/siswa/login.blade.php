@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center font-weight-light my-2">{{ __('Login Siswa') }}</h3>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('siswa.login') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="nis" type="text" class="form-control @error('nis') is-invalid @enderror" name="nis" value="{{ old('nis') }}" required autocomplete="nis" autofocus placeholder="NIS">
                            <label for="nis">{{ __('Nomor Induk Siswa (NIS)') }}</label>
                            @error('nis')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                            <label for="password">{{ __('Password') }}</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ __('Login') }}
                            </button>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            @if (Route::has('siswa.password.request'))
                                <a class="text-decoration-none small" href="{{ route('siswa.password.request') }}">
                                    {{ __('Lupa Password?') }}
                                </a>
                            @endif
                            
                            @if (Route::has('siswa.register'))
                                <a class="text-decoration-none small" href="{{ route('siswa.register') }}">
                                    {{ __('Belum punya akun? Daftar') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small">
                        <a href="{{ route('admin.login') }}" class="text-decoration-none">Login sebagai Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 