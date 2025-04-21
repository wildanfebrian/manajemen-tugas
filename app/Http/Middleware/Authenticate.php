<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('admin/*') || $request->is('kelas/*') || $request->is('mapel/*') || 
            $request->is('siswa/*') || $request->is('tugas/*') || $request->is('nilai/*')) {
            return route('admin.login');
        }
        
        return route('login');
    }
} 