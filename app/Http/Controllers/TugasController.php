<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Mapel;
use App\Models\Kelas;
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
        })->with(['mapel', 'kelas'])->get();

        $mapel = Mapel::all();
        $kelas = Kelas::all();
        
        return view('tugas.index', compact('tugas', 'mapel', 'kelas'));
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
            'filetugas' => 'required|file|max:5120', // 5MB max
            'kelas_id' => 'required|array',
            'kelas_id.*' => 'exists:kelas,id'
        ]);

        try {
            if ($request->hasFile('filetugas')) {
                $file = $request->file('filetugas');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('tugas', $fileName, 'public');
                
                $tugas = Tugas::create([
                    'mapel_id' => $request->mapel_id,
                    'filetugas' => $filePath,
                ]);

                // Attach selected classes
                $tugas->kelas()->attach($request->kelas_id);

                return redirect()->route('admin.tugas.index')
                    ->with('success', 'Tugas berhasil ditambahkan');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan tugas: ' . $e->getMessage())
                ->withInput();
        }
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'filetugas' => 'nullable|file|max:10240', // 10MB max
            'kelas_id' => 'required|array',
            'kelas_id.*' => 'exists:kelas,id'
        ]);

        try {
            $tugas = Tugas::findOrFail($id);
            $data = ['mapel_id' => $request->mapel_id];

            if ($request->hasFile('filetugas')) {
                // Delete old file if it exists
                if ($tugas->filetugas && file_exists(storage_path('app/public/' . $tugas->filetugas))) {
                    unlink(storage_path('app/public/' . $tugas->filetugas));
                }
                
                // Store new file
                $file = $request->file('filetugas');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('tugas', $fileName, 'public');
                
                // Store the file path, not the file contents
                $data['filetugas'] = $filePath;
            }

            $tugas->update($data);
            
            // Sync selected classes
            $tugas->kelas()->sync($request->kelas_id);

            return redirect()->route('admin.tugas.index')
                ->with('success', 'Tugas berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui tugas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $tugas = Tugas::findOrFail($id);
            
            // Delete the file if it exists
            if ($tugas->filetugas && file_exists(storage_path('app/public/' . $tugas->filetugas))) {
                unlink(storage_path('app/public/' . $tugas->filetugas));
            }
            
            // Detach kelas relationships
            $tugas->kelas()->detach();
            
            // Delete the tugas
            $tugas->delete();

            return redirect()->route('admin.tugas.index')
                ->with('success', 'Tugas berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.tugas.index')
                ->with('error', 'Terjadi kesalahan saat menghapus tugas: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        try {
            $tugas = Tugas::findOrFail($id);
            
            if (!$tugas->filetugas || !file_exists(storage_path('app/public/' . $tugas->filetugas))) {
                return redirect()->back()
                    ->with('error', 'File tugas tidak ditemukan');
            }

            return response()->download(storage_path('app/public/' . $tugas->filetugas));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunduh file: ' . $e->getMessage());
        }
    }
}
