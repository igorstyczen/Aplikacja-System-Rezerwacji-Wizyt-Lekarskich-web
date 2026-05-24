<?php

namespace App\Http\Controllers;

use App\Models\DoctorApplication;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorApplicationController extends Controller
{
    public function create()
    {
        $existingApplication = DoctorApplication::where('user_id', Auth::id())
            ->latest()
            ->first();

        $specializations = Specialization::query()
            ->orderBy('name')
            ->get();

        return view('doctor-applications.create', [
            'existingApplication' => $existingApplication,
            'specializations' => $specializations,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:25',
                'regex:/^(\+|00)?[0-9\s\-]{9,20}$/',
            ],
            'bio' => ['required', 'string', 'min:20', 'max:3000'],

            'specializations' => ['required', 'array', 'min:1'],
            'specializations.*' => ['integer', 'exists:specializations,id'],

            'clinic_name' => ['required', 'string', 'max:255'],
            'clinic_city' => ['required', 'string', 'max:255'],
            'clinic_address' => ['required', 'string', 'max:255'],
            'clinic_details' => ['nullable', 'string', 'max:2000'],

            'is_for_adults' => ['nullable', 'boolean'],
            'is_for_children' => ['nullable', 'boolean'],
        ], [
            'phone.required' => 'Numer telefonu jest wymagany.',
            'phone.regex' => 'Podaj poprawny numer telefonu, np. 500 600 700, +48 500 600 700 albo +380 123 456 789.',
        ]);

        $hasPendingApplication = DoctorApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingApplication) {
            return back()->withErrors([
                'application' => 'Masz już oczekujące zgłoszenie o profil lekarza.',
            ]);
        }

        DoctorApplication::create([
            'user_id' => Auth::id(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'specialization_ids' => $request->input('specializations', []),

            'clinic_name' => $request->clinic_name,
            'clinic_city' => $request->clinic_city,
            'clinic_address' => $request->clinic_address,
            'clinic_details' => $request->clinic_details,

            'is_for_adults' => $request->boolean('is_for_adults'),
            'is_for_children' => $request->boolean('is_for_children'),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('doctor-applications.create')
            ->with('success', 'Zgłoszenie zostało wysłane do administratora.');
    }
}
