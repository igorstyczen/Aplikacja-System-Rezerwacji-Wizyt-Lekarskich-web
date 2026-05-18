<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardRedirectController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'doctor') {
            return redirect()->route('doctor.schedule');
        }

        if ($user->role === 'patient') {
            return redirect()->route('patient.appointments');
        }

        return view('dashboard');
    }
}
