<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $siswa = Siswa::when($search, function($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%")
                        ->orWhereHas('kelas', function($query) use ($search) {
                            $query->where('nama_kelas', 'like', "%{$search}%");
                        });
        })->with('kelas')->get();

        $kelas = Kelas::all();
        
        return view('siswa.index', compact('siswa', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:255|unique:siswas',
            'password' => 'required|string|min:6',
        ]);

        Siswa::create([
            'kelas_id' => $request->kelas_id,
            'nama' => $request->nama,
            'nis' => $request->nis,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:255|unique:siswas,nis,' . $siswa->id,
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'kelas_id' => $request->kelas_id,
            'nama' => $request->nama,
            'nis' => $request->nis,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $siswa->update($data);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus');
    }
}
