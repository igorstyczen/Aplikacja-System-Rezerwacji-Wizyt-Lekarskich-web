<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $doctorsCount = Doctor::count();
        $patientsCount = Patient::count();
        $appointmentsCount = Appointment::count();

        $pendingAppointmentsCount = Appointment::where('status', 'pending')->count();
        $confirmedAppointmentsCount = Appointment::where('status', 'confirmed')->count();
        $cancelledAppointmentsCount = Appointment::where('status', 'cancelled')->count();
        $completedAppointmentsCount = Appointment::where('status', 'completed')->count();

        $latestAppointments = Appointment::with([
                'patient',
                'doctor',
                'service',
                'clinic',
            ])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'usersCount' => $usersCount,
            'doctorsCount' => $doctorsCount,
            'patientsCount' => $patientsCount,
            'appointmentsCount' => $appointmentsCount,
            'pendingAppointmentsCount' => $pendingAppointmentsCount,
            'confirmedAppointmentsCount' => $confirmedAppointmentsCount,
            'cancelledAppointmentsCount' => $cancelledAppointmentsCount,
            'completedAppointmentsCount' => $completedAppointmentsCount,
            'latestAppointments' => $latestAppointments,
        ]);
    }

    public function users()
    {
        $users = User::query()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', [
            'users' => $users,
        ]);
    }

    public function doctors()
    {
        $doctors = Doctor::with([
                'user',
                'specializations',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.doctors', [
            'doctors' => $doctors,
        ]);
    }

    public function toggleDoctorVerification(Doctor $doctor)
    {
        $doctor->update([
            'is_verified' => ! $doctor->is_verified,
        ]);

        if ($doctor->is_verified) {
            return back()->with('success', 'Lekarz został zweryfikowany.');
        }

        return back()->with('success', 'Weryfikacja lekarza została cofnięta.');
    }
}
