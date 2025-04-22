<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SiswaAuthController extends Controller
{
    protected $redirectTo = '/siswa/dashboard';

    public function __construct()
    {
        $this->middleware('guest:siswa')->except(['logout', 'dashboard']);
    }

    public function showLoginForm()
    {
        return view('auth.siswa.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.siswa.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:20', 'unique:siswas'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:siswas'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $siswa = $this->create($request->all());

        Auth::guard('siswa')->login($siswa);

        return redirect($this->redirectTo);
    }

    protected function create(array $data)
    {
        return Siswa::create([
            'name' => $data['name'],
            'nis' => $data['nis'],
            'email' => $data['email'],
            'kelas_id' => $data['kelas_id'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectTo);
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    public function username()
    {
        return 'nis';
    }

    protected function attemptLogin(Request $request)
    {
        return Auth::guard('siswa')->attempt(
            $this->credentials($request), $request->boolean('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    public function logout(Request $request)
    {
        Auth::guard('siswa')->logout();
        
        $request->session()->invalidate();
        
        $request->session()->regenerateToken();
        
        return redirect()->route('siswa.login');
    }

    public function dashboard()
    {
        $siswa = Auth::guard('siswa')->user();
        $tugas = Tugas::whereHas('kelas', function($query) use ($siswa) {
            $query->where('kelas.id', $siswa->kelas_id);
        })->with(['mapel', 'kelas'])->get();
        
        return view('siswa.dashboard', compact('siswa', 'tugas'));
    }
} 