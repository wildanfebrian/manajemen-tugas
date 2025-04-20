<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $tugas = Tugas::when($search, function($query) use ($search) {
            return $query->whereHas('mapel', function($query) use ($search) {
                $query->where('materi', 'like', "%{$search}%");
            });
        })->with('mapel')->get();

        $mapel = Mapel::all();
        
        return view('tugas.index', compact('tugas', 'mapel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mapel = Mapel::all();
        return view('tugas.create', compact('mapel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'filetugas' => 'required|file|max:10240', // Max 10MB
        ]);

        $file = $request->file('filetugas');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('tugas', $fileName, 'public');

        Tugas::create([
            'mapel_id' => $request->mapel_id,
            'filetugas' => $filePath,
        ]);

        return redirect()->route('tugas.index')
            ->with('success', 'Tugas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tugas $tugas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tugas $tugas)
    {
        $mapel = Mapel::all();
        return view('tugas.edit', compact('tugas', 'mapel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tugas $tugas)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'filetugas' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $data = [
            'mapel_id' => $request->mapel_id,
        ];

        if ($request->hasFile('filetugas')) {
            // Delete old file if it exists
            if ($tugas->filetugas && Storage::disk('public')->exists($tugas->filetugas)) {
                Storage::disk('public')->delete($tugas->filetugas);
            }
            
            // Store new file
            $file = $request->file('filetugas');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('tugas', $fileName, 'public');
            $data['filetugas'] = $filePath;
        }

        $tugas->update($data);

        return redirect()->route('tugas.index')
            ->with('success', 'Tugas berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tugas $tugas)
    {
        // Delete file if it exists
        if ($tugas->filetugas && Storage::disk('public')->exists($tugas->filetugas)) {
            Storage::disk('public')->delete($tugas->filetugas);
        }
        
        $tugas->delete();

        return redirect()->route('tugas.index')
            ->with('success', 'Tugas berhasil dihapus');
    }

    public function download(Tugas $tugas)
    {
        if (!$tugas->filetugas || !Storage::disk('public')->exists($tugas->filetugas)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }
        
        return Storage::disk('public')->download($tugas->filetugas);
    }
}
