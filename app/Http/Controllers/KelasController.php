<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Requests\StoreKelasRequest;
use App\Http\Requests\UpdateKelasRequest;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $kelas = Kelas::when($search, function($query) use ($search) {
            return $query->where('namakelas', 'like', "%{$search}%")
                        ->orWhere('peryataan', 'like', "%{$search}%");
        })->get();
        
        return view('kelas.index', compact('kelas'));
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
    public function store(StoreKelasRequest $request)
    {
        Kelas::create($request->all());
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            
            $validated = $request->validate([
                'namakelas' => 'required|string|max:255',
                'peryataan' => 'required|string|max:255'
            ]);

            $kelas->update($validated);
            
            return redirect()->route('kelas.index')
                ->with('success', 'Kelas berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengubah kelas: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            
            // Check if there are any related records
            if ($kelas->siswa()->exists()) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus kelas karena masih memiliki data siswa terkait');
            }

            $kelas->delete();
            
            return redirect()->route('kelas.index')
                ->with('success', 'Kelas berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus kelas: ' . $e->getMessage());
        }
    }
}
