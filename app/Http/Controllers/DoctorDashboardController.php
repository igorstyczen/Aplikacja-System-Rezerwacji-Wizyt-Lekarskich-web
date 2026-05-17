<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailabilitySlot;
use App\Models\Doctor;
use Illuminate\Http\Request;
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

    public function confirmAppointment(Appointment $appointment)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back()->withErrors([
                'status' => 'Można potwierdzić tylko wizytę oczekującą.',
            ]);
        }

        $appointment->update([
            'status' => 'confirmed',
        ]);

        return back()->with('success', 'Wizyta została potwierdzona.');
    }

    public function completeAppointment(Appointment $appointment)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($appointment->status !== 'confirmed') {
            return back()->withErrors([
                'status' => 'Można zakończyć tylko wizytę potwierdzoną.',
            ]);
        }

        $appointment->update([
            'status' => 'completed',
        ]);

        return back()->with('success', 'Wizyta została zakończona.');
    }

    public function profile()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            return view('doctor.profile', [
                'doctor' => null,
                'message' => 'Nie masz profilu lekarza.',
            ]);
        }

        return view('doctor.profile', [
            'doctor' => $doctor,
            'message' => null,
        ]);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            abort(403);
        }

        $file = $request->file('photo');
        $path = $file->store('doctor-avatars', 'public');

        $doctor->update([
            'photo_url' => 'storage/' . $path,
        ]);

        return back()->with('success', 'Zdjęcie profilowe zostało zaktualizowane.');
    }
}

