<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    public function appointments()
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        if (! $patient) {
            return view('patient.appointments', [
                'appointments' => collect(),
                'message' => 'Nie masz jeszcze profilu pacjenta.',
            ]);
        }

        $appointments = Appointment::with([
                'doctor.specializations',
                'clinic',
                'service',
            ])
            ->where('patient_id', $patient->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('patient.appointments', [
            'appointments' => $appointments,
            'message' => null,
        ]);
    }
}
