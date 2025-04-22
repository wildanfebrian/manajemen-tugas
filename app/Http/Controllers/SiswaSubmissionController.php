<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiswaSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:siswa');
    }
    
    /**
     * Display a listing of the tugas.
     */
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        $tugas = Tugas::whereHas('kelas', function($query) use ($siswa) {
            $query->where('kelas.id', $siswa->kelas_id);
        })->with(['mapel', 'kelas', 'nilai'])->get();
        
        // Get submitted assignments for this student
        $submissions = Nilai::where('siswa_id', $siswa->id)
            ->pluck('tugas_id')
            ->toArray();
        
        return view('siswa.tugas.index', compact('tugas', 'submissions', 'siswa'));
    }
    
    /**
     * Show the tugas details.
     */
    public function show($id)
    {
        $siswa = Auth::guard('siswa')->user();
        $tugas = Tugas::with(['mapel', 'kelas'])->findOrFail($id);
        
        // Check if student has already submitted this assignment
        $submission = Nilai::where('siswa_id', $siswa->id)
            ->where('tugas_id', $tugas->id)
            ->first();
        
        // Check if the tugas belongs to student's class
        $hasAccess = $tugas->kelas->contains(function($kelas) use ($siswa) {
            return $kelas->id === $siswa->kelas_id;
        });
        
        if (!$hasAccess) {
            return redirect()->route('siswa.tugas.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }
        
        return view('siswa.tugas.show', compact('tugas', 'submission', 'siswa'));
    }
    
    /**
     * Download the tugas file.
     */
    public function download($id)
    {
        $siswa = Auth::guard('siswa')->user();
        $tugas = Tugas::findOrFail($id);
        
        // Check if the tugas belongs to student's class
        $hasAccess = $tugas->kelas->contains(function($kelas) use ($siswa) {
            return $kelas->id === $siswa->kelas_id;
        });
        
        if (!$hasAccess) {
            return redirect()->route('siswa.tugas.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }
        
        if (!$tugas->filetugas || !file_exists(storage_path('app/public/' . $tugas->filetugas))) {
            return redirect()->back()
                ->with('error', 'File tugas tidak ditemukan');
        }

        return response()->download(storage_path('app/public/' . $tugas->filetugas));
    }
    
    /**
     * Submit a tugas.
     */
    public function submit(Request $request, $id)
    {
        $request->validate([
            'hasil' => 'required|file|max:10240',
        ]);

        $siswa = Auth::guard('siswa')->user();
        $tugas = Tugas::findOrFail($id);
        
        // Check if the tugas belongs to student's class
        $hasAccess = $tugas->kelas->contains(function($kelas) use ($siswa) {
            return $kelas->id === $siswa->kelas_id;
        });
        
        if (!$hasAccess) {
            return redirect()->route('siswa.tugas.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }
        
        // Check if student has already submitted this assignment
        $existingSubmission = Nilai::where('siswa_id', $siswa->id)
            ->where('tugas_id', $tugas->id)
            ->first();
            
        if ($existingSubmission) {
            return redirect()->back()
                ->with('error', 'Anda sudah mengumpulkan tugas ini sebelumnya.');
        }
        
        try {
            if ($request->hasFile('hasil')) {
                $file = $request->file('hasil');
                $fileName = time() . '_' . $siswa->id . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('submissions', $fileName, 'public');
                
                // Create submission record
                Nilai::create([
                    'siswa_id' => $siswa->id,
                    'tugas_id' => $tugas->id,
                    'hasil' => $filePath,
                    'tanggal' => time(),
                    'status' => 'submitted'
                ]);
                
                return redirect()->route('siswa.tugas.show', $tugas->id)
                    ->with('success', 'Tugas berhasil dikumpulkan.');
            }
            
            return redirect()->back()
                ->with('error', 'Tidak ada file yang diunggah.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengumpulkan tugas: ' . $e->getMessage());
        }
    }

    /**
     * Download the student's submitted file.
     */
    public function downloadSubmission($id)
    {
        $siswa = Auth::guard('siswa')->user();
        
        // Find the submission
        $submission = Nilai::where('id', $id)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();
        
        // Check if the file exists
        if (!$submission->hasil || !file_exists(storage_path('app/public/' . $submission->hasil))) {
            return redirect()->back()
                ->with('error', 'File submission tidak ditemukan');
        }

        return response()->download(storage_path('app/public/' . $submission->hasil));
    }
} 