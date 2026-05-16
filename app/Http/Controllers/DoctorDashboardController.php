<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailabilitySlot;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    public function schedule()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            return view('doctor.schedule', [
                'doctor' => null,
                'slots' => collect(),
                'message' => 'Nie masz profilu lekarza.',
            ]);
        }

        $slots = AvailabilitySlot::with('clinic')
            ->where('doctor_id', $doctor->id)
            ->orderBy('start_time')
            ->get();

        return view('doctor.schedule', [
            'doctor' => $doctor,
            'slots' => $slots,
            'message' => null,
        ]);
    }

    public function appointments()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            return view('doctor.appointments', [
                'doctor' => null,
                'appointments' => collect(),
                'message' => 'Nie masz profilu lekarza.',
            ]);
        }

        $appointments = Appointment::with([
                'patient',
                'service',
                'clinic',
            ])
            ->where('doctor_id', $doctor->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('doctor.appointments', [
            'doctor' => $doctor,
            'appointments' => $appointments,
            'message' => null,
        ]);
    }
}
