<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailabilitySlot;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        $users = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users', [
            'users' => $users,
        ]);
    }

    public function editUser(User $user)
    {
        return view('admin.edit-user', [
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'in:patient,doctor,admin'],
        ]);

        if ($user->id === Auth::id() && $request->role !== 'admin') {
            return back()->withErrors([
                'role' => 'Nie możesz odebrać sobie roli administratora.',
            ]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', 'Dane użytkownika zostały zaktualizowane.');
    }

    public function doctors(Request $request)
    {
        $query = Doctor::with([
            'user',
            'specializations',
        ]);

        if ($request->filled('name')) {
            $query->where(function ($doctorQuery) use ($request) {
                $doctorQuery
                    ->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('email')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('email', 'like', '%' . $request->email . '%');
            });
        }

        if ($request->filled('specialization')) {
            $query->whereHas('specializations', function ($specializationQuery) use ($request) {
                $specializationQuery->where('specialization_name', 'like', '%' . $request->specialization . '%');
            });
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }

        $doctors = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.doctors', [
            'doctors' => $doctors,
        ]);
    }

    public function createDoctor()
    {
        return view('admin.create-doctor');
    }

    public function storeDoctor(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'is_for_adults' => ['nullable', 'boolean'],
            'is_for_children' => ['nullable', 'boolean'],
            'is_verified' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'doctor',
                'is_active' => true,
            ]);

            $doctorData = [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'bio' => $request->bio,
                'is_for_adults' => $request->boolean('is_for_adults'),
                'is_for_children' => $request->boolean('is_for_children'),
                'is_verified' => $request->boolean('is_verified'),
            ];

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('doctor-avatars', 'public');
                $doctorData['photo_url'] = 'storage/' . $path;
            }

            Doctor::create($doctorData);
        });

        return redirect()
            ->route('admin.doctors')
            ->with('success', 'Lekarz został dodany.');
    }

    public function editDoctor(Doctor $doctor)
    {
        $doctor->load([
            'user',
            'specializations',
        ]);

        return view('admin.edit-doctor', [
            'doctor' => $doctor,
        ]);
    }

    public function updateDoctor(Request $request, Doctor $doctor)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'is_for_adults' => ['nullable', 'boolean'],
            'is_for_children' => ['nullable', 'boolean'],
            'is_verified' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'bio' => $request->bio,
            'is_for_adults' => $request->boolean('is_for_adults'),
            'is_for_children' => $request->boolean('is_for_children'),
            'is_verified' => $request->boolean('is_verified'),
        ];

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('doctor-avatars', 'public');
            $data['photo_url'] = 'storage/' . $path;
        }

        $doctor->update($data);

        return redirect()
            ->route('admin.doctors')
            ->with('success', 'Profil lekarza został zaktualizowany.');
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

    public function appointments(Request $request)
    {
        $query = Appointment::with([
            'patient',
            'doctor',
            'service',
            'clinic',
        ]);

        if ($request->filled('patient')) {
            $query->whereHas('patient', function ($patientQuery) use ($request) {
                $patientQuery
                    ->where('first_name', 'like', '%' . $request->patient . '%')
                    ->orWhere('last_name', 'like', '%' . $request->patient . '%')
                    ->orWhere('phone', 'like', '%' . $request->patient . '%');
            });
        }

        if ($request->filled('doctor')) {
            $query->whereHas('doctor', function ($doctorQuery) use ($request) {
                $doctorQuery
                    ->where('first_name', 'like', '%' . $request->doctor . '%')
                    ->orWhere('last_name', 'like', '%' . $request->doctor . '%');
            });
        }

        if ($request->filled('clinic')) {
            $query->whereHas('clinic', function ($clinicQuery) use ($request) {
                $clinicQuery
                    ->where('name', 'like', '%' . $request->clinic . '%')
                    ->orWhere('city', 'like', '%' . $request->clinic . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        $appointments = $query
            ->orderBy('date', 'desc')
            ->paginate(20)
            ->withQueryString();

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

        if ($user->id === Auth::id()) {
            return back()->withErrors([
                'role' => 'Nie możesz zmienić własnej roli, aby nie utracić dostępu do panelu administratora.',
            ]);
        }

        $user->update([
            'role' => $request->role,
        ]);

        return back()->with('success', 'Rola użytkownika została zmieniona.');
    }

    public function toggleUserActive(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors([
                'user' => 'Nie możesz dezaktywować własnego konta administratora.',
            ]);
        }

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        if ($user->is_active) {
            return back()->with('success', 'Użytkownik został aktywowany.');
        }

        return back()->with('success', 'Użytkownik został dezaktywowany.');
    }

    public function toggleDoctorActive(Doctor $doctor)
    {
        $doctor->update([
            'is_active' => ! $doctor->is_active,
        ]);

        if ($doctor->is_active) {
            return back()->with('success', 'Lekarz został aktywowany.');
        }

        return back()->with('success', 'Lekarz został dezaktywowany.');
    }
}
