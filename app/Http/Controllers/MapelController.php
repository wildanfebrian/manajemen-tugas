<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mapel = Mapel::all();
        return view('mapel.index', compact('mapel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mapel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'materi' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
        ]);

        Mapel::create($request->all());

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mapel berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mapel $mapel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mapel $mapel)
    {
        return view('mapel.edit', compact('mapel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mapel $mapel)
    {
        $request->validate([
            'materi' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
        ]);

        $mapel->update($request->all());

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mapel berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mapel $mapel)
    {
        $mapel->delete();

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mapel berhasil dihapus');
    }
}
