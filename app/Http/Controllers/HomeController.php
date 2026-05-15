<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::with([
            'specializations',
            'helpTags',
            'clinics',
        ])
            ->where('is_verified', true)
            ->paginate(50);

        return view('home', compact('doctors'));
    }
}
