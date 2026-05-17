<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailabilitySlot;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

    public function appointments()
    {
        $appointments = Appointment::with([
                'patient',
                'doctor',
                'service',
                'clinic',
            ])
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('admin.appointments', [
            'appointments' => $appointments,
        ]);
    }

    public function confirmAppointment(Appointment $appointment)
    {
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

    public function cancelAppointment(Appointment $appointment)
    {
        if ($appointment->status === 'cancelled') {
            return back()->with('success', 'Ta wizyta jest już anulowana.');
        }

        DB::transaction(function () use ($appointment) {
            $appointment->update([
                'status' => 'cancelled',
            ]);

            $slot = AvailabilitySlot::where('doctor_id', $appointment->doctor_id)
                ->where('clinic_id', $appointment->clinic_id)
                ->where('start_time', $appointment->date)
                ->lockForUpdate()
                ->first();

            if ($slot) {
                $slot->update([
                    'status' => 'available',
                ]);
            }
        });

        return back()->with('success', 'Wizyta została anulowana.');
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:patient,doctor,admin'],
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return back()->with('success', 'Rola użytkownika została zmieniona.');
    }
}
