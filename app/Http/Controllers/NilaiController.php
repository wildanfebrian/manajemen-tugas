<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Tugas;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $nilai = Nilai::when($search, function($query) use ($search) {
            return $query->whereHas('siswa', function($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%");
            })->orWhereHas('tugas', function($query) use ($search) {
                $query->whereHas('mapel', function($query) use ($search) {
                    $query->where('materi', 'like', "%{$search}%");
                });
            });
        })->with(['siswa', 'tugas.mapel'])->get();

        $siswa = Siswa::all();
        $tugas = Tugas::with('mapel')->get();
        
        return view('nilai.index', compact('nilai', 'siswa', 'tugas'));
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
            'siswa_id' => 'required|exists:siswas,id',
            'tugas_id' => 'required|exists:tugas,id',
            'hasil' => 'required|numeric|min:0|max:100',
            'tanggal' => 'required|date',
        ]);

        Nilai::create($request->all());

        return redirect()->route('nilai.index')
            ->with('success', 'Nilai berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Nilai $nilai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nilai $nilai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nilai $nilai)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tugas_id' => 'required|exists:tugas,id',
            'hasil' => 'required|numeric|min:0|max:100',
            'tanggal' => 'required|date',
        ]);

        $nilai->update($request->all());

        return redirect()->route('nilai.index')
            ->with('success', 'Nilai berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nilai $nilai)
    {
        $nilai->delete();

        return redirect()->route('nilai.index')
            ->with('success', 'Nilai berhasil dihapus');
    }
}
