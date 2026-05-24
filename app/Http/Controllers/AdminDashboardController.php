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
use App\Models\Clinic;
use App\Models\DoctorApplication;
use App\Models\DoctorSpecialization;
use App\Models\Specialization;
use App\Models\HelpTag;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $doctorsCount = Doctor::count();
        $patientsCount = Patient::count();
        $appointmentsCount = Appointment::count();

        $pendingPaymentAppointmentsCount = Appointment::where('status', 'pending_payment')->count();
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
            'pendingPaymentAppointmentsCount' => $pendingPaymentAppointmentsCount,
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
        $specializations = Specialization::query()
            ->orderBy('name')
            ->get();

        $helpTags = HelpTag::query()
            ->orderBy('tag_name')
            ->get();

        return view('admin.create-doctor', [
            'specializations' => $specializations,
            'helpTags' => $helpTags,
        ]);
    }

    public function storeDoctor(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => [
                'nullable',
                'string',
                'max:25',
                'regex:/^(\+|00)?[0-9\s\-]{9,20}$/',
            ],
            'password' => ['required', 'string', 'min:8'],

            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:3000'],

            'specializations' => ['required', 'array', 'min:1'],
            'specializations.*' => ['integer', 'exists:specializations,id'],

            'help_tags' => ['nullable', 'array'],
            'help_tags.*' => ['integer', 'exists:help_tags,id'],

            'is_for_adults' => ['nullable', 'boolean'],
            'is_for_children' => ['nullable', 'boolean'],
            'is_verified' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'phone.regex' => 'Podaj poprawny numer telefonu, np. 500 600 700, +48 500 600 700 albo +380 123 456 789.',
            'specializations.required' => 'Wybierz przynajmniej jedną specjalizację.',
            'specializations.min' => 'Wybierz przynajmniej jedną specjalizację.',
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

            $doctor = Doctor::create($doctorData);

            foreach ($request->input('specializations', []) as $specializationId) {
                $specialization = Specialization::find($specializationId);

                if ($specialization) {
                    DoctorSpecialization::create([
                        'doctor_id' => $doctor->id,
                        'specialization_id' => $specialization->id,
                        'specialization_name' => $specialization->name,
                    ]);
                }
            }

            $doctor->helpTags()->sync($request->input('help_tags', []));
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

    public function clinics(Request $request)
    {
        $query = Clinic::with('doctors');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('doctor')) {
            $query->whereHas('doctors', function ($doctorQuery) use ($request) {
                $doctorQuery
                    ->where('first_name', 'like', '%' . $request->doctor . '%')
                    ->orWhere('last_name', 'like', '%' . $request->doctor . '%');
            });
        }

        $clinics = $query
            ->orderBy('city')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $doctors = Doctor::query()
            ->where('is_active', true)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('admin.clinics', [
            'clinics' => $clinics,
            'doctors' => $doctors,
        ]);
    }

    public function storeClinic(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:2000'],
            'doctors' => ['nullable', 'array'],
            'doctors.*' => ['integer', 'exists:doctors,id'],
        ]);

        DB::transaction(function () use ($request) {
            $clinic = Clinic::create([
                'doctor_id' => null,
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city,
                'details' => $request->details,
            ]);

            $clinic->doctors()->sync($request->input('doctors', []));
        });

        return back()->with('success', 'Klinika została dodana.');
    }

    public function updateClinic(Request $request, Clinic $clinic)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:2000'],
            'doctors' => ['nullable', 'array'],
            'doctors.*' => ['integer', 'exists:doctors,id'],
        ]);

        DB::transaction(function () use ($request, $clinic) {
            $clinic->update([
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city,
                'details' => $request->details,
            ]);

            $clinic->doctors()->sync($request->input('doctors', []));
        });

        return back()->with('success', 'Klinika została zaktualizowana.');
    }

    public function deleteClinic(Clinic $clinic)
    {
        if ($clinic->services()->exists()) {
            return back()->withErrors([
                'clinic' => 'Nie można usunąć kliniki, ponieważ są do niej przypisane usługi.',
            ]);
        }

        if ($clinic->availabilitySlots()->exists()) {
            return back()->withErrors([
                'clinic' => 'Nie można usunąć kliniki, ponieważ są do niej przypisane terminy w grafiku.',
            ]);
        }

        if ($clinic->appointments()->exists()) {
            return back()->withErrors([
                'clinic' => 'Nie można usunąć kliniki, ponieważ są do niej przypisane wizyty.',
            ]);
        }

        DB::transaction(function () use ($clinic) {
            $clinic->doctors()->detach();
            $clinic->delete();
        });

        return back()->with('success', 'Klinika została usunięta.');
    }

    public function doctorApplications()
    {
        $applications = DoctorApplication::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $specializations = Specialization::query()
            ->orderBy('name')
            ->get();

        return view('admin.doctor-applications', [
            'applications' => $applications,
            'specializations' => $specializations,
        ]);
    }

    public function approveDoctorApplication(DoctorApplication $doctorApplication)
    {
        if ($doctorApplication->status !== 'pending') {
            return back()->withErrors([
                'application' => 'To zgłoszenie zostało już rozpatrzone.',
            ]);
        }

        DB::transaction(function () use ($doctorApplication) {
            $user = $doctorApplication->user;

            $user->update([
                'role' => 'doctor',
                'phone' => $doctorApplication->phone,
            ]);

            $doctor = Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $doctorApplication->first_name,
                    'last_name' => $doctorApplication->last_name,
                    'bio' => $doctorApplication->bio,
                    'is_verified' => true,
                    'is_for_adults' => $doctorApplication->is_for_adults,
                    'is_for_children' => $doctorApplication->is_for_children,
                ]
            );

            $doctor->specializations()->delete();

            $rawSpecializationIds = $doctorApplication->specialization_ids;

            if (is_string($rawSpecializationIds)) {
                $rawSpecializationIds = json_decode($rawSpecializationIds, true) ?? [];
            }

            foreach ($rawSpecializationIds ?? [] as $specializationId) {
                $specialization = Specialization::find($specializationId);

                if ($specialization) {
                    DoctorSpecialization::create([
                        'doctor_id' => $doctor->id,
                        'specialization_id' => $specialization->id,
                        'specialization_name' => $specialization->name,
                    ]);
                }
            }

            $clinic = Clinic::query()
                ->where('city', $doctorApplication->clinic_city)
                ->where('address', $doctorApplication->clinic_address)
                ->first();

            if (! $clinic) {
                $clinic = Clinic::create([
                    'doctor_id' => null,
                    'name' => $doctorApplication->clinic_name,
                    'city' => $doctorApplication->clinic_city,
                    'address' => $doctorApplication->clinic_address,
                    'details' => $doctorApplication->clinic_details,
                ]);
            }

            $doctor->clinics()->syncWithoutDetaching([$clinic->id]);

            $doctorApplication->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                'admin_note' => 'Zgłoszenie zaakceptowane.',
            ]);
        });

        return back()->with('success', 'Zgłoszenie zostało zaakceptowane. Użytkownik otrzymał profil lekarza.');
    }

    public function rejectDoctorApplication(Request $request, DoctorApplication $doctorApplication)
    {
        $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($doctorApplication->status !== 'pending') {
            return back()->withErrors([
                'application' => 'To zgłoszenie zostało już rozpatrzone.',
            ]);
        }

        $doctorApplication->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'admin_note' => $request->admin_note ?: 'Zgłoszenie odrzucone.',
        ]);

        return back()->with('success', 'Zgłoszenie zostało odrzucone.');
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

        if ($appointment->payment_status !== 'paid') {
            return back()->withErrors([
                'payment' => 'Nie można potwierdzić wizyty, która nie została opłacona.',
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
