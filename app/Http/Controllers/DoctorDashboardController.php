<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailabilitySlot;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DoctorSpecialization;
use App\Models\HelpTag;

class DoctorDashboardController extends Controller
{
    public function schedule()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            return view('doctor.schedule', [
                'doctor' => null,
                'slots' => collect(),
                'clinics' => collect(),
                'message' => 'Nie masz profilu lekarza.',
            ]);
        }

        $doctor->load('clinics');

        $slots = AvailabilitySlot::with('clinic')
            ->where('doctor_id', $doctor->id)
            ->orderBy('start_time')
            ->get();

        return view('doctor.schedule', [
            'doctor' => $doctor,
            'slots' => $slots,
            'clinics' => $doctor->clinics,
            'message' => null,
        ]);
    }

    public function storeScheduleSlots(Request $request)
    {
        $request->validate([
            'clinic_id' => ['required', 'exists:clinics,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration' => ['required', 'integer', 'min:10', 'max:180'],
            'repeat_weekly' => ['nullable', 'boolean'],
        ]);

        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            abort(403);
        }

        $clinicBelongsToDoctor = $doctor->clinics()
            ->where('clinics.id', $request->clinic_id)
            ->exists();

        if (! $clinicBelongsToDoctor) {
            return back()->withErrors([
                'clinic_id' => 'Nie możesz dodać terminu do kliniki, która nie jest przypisana do Twojego profilu.',
            ]);
        }

        $baseDate = \Carbon\Carbon::parse($request->date);
        $duration = (int) $request->slot_duration;

        /*
            Jeżeli repeat_weekly jest zaznaczone:
            - tworzymy terminy dla wybranego dnia
            - oraz dla tego samego dnia tygodnia przez kolejne 4 tygodnie

            Czyli np. poniedziałek:
            25.05, 01.06, 08.06, 15.06, 22.06
        */
        $weeksToCreate = $request->boolean('repeat_weekly') ? 4 : 0;

        $createdSlots = 0;
        $skippedSlots = 0;

        DB::transaction(function () use ($doctor, $request, $baseDate, $duration, $weeksToCreate, &$createdSlots, &$skippedSlots) {
            for ($week = 0; $week <= $weeksToCreate; $week++) {
                $date = $baseDate->copy()->addWeeks($week)->format('Y-m-d');

                $start = \Carbon\Carbon::parse($date . ' ' . $request->start_time);
                $end = \Carbon\Carbon::parse($date . ' ' . $request->end_time);

                $current = $start->copy();

                while ($current->copy()->addMinutes($duration)->lessThanOrEqualTo($end)) {
                    $slotStart = $current->copy();
                    $slotEnd = $current->copy()->addMinutes($duration);

                    $exists = AvailabilitySlot::where('doctor_id', $doctor->id)
                        ->where('clinic_id', $request->clinic_id)
                        ->where(function ($query) use ($slotStart, $slotEnd) {
                            $query->where('start_time', '<', $slotEnd)
                                ->where('end_time', '>', $slotStart);
                        })
                        ->exists();

                    if (! $exists) {
                        AvailabilitySlot::create([
                            'doctor_id' => $doctor->id,
                            'clinic_id' => $request->clinic_id,
                            'start_time' => $slotStart,
                            'end_time' => $slotEnd,
                            'status' => 'available',
                        ]);

                        $createdSlots++;
                    } else {
                        $skippedSlots++;
                    }

                    $current->addMinutes($duration);
                }
            }
        });

        if ($createdSlots === 0) {
            return back()->withErrors([
                'slots' => 'Nie dodano nowych terminów. Wybrany zakres pokrywa się z istniejącym grafikiem.',
            ]);
        }

        $message = 'Dodano nowe terminy do grafiku: ' . $createdSlots . '.';

        if ($skippedSlots > 0) {
            $message .= ' Pominięto istniejące lub nakładające się terminy: ' . $skippedSlots . '.';
        }

        if ($request->boolean('repeat_weekly')) {
            $message .= ' Terminy zostały powtórzone co tydzień przez miesiąc.';
        }

        return back()->with('success', $message);
    }

    public function editScheduleSlot(AvailabilitySlot $slot)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor || $slot->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($slot->status !== 'available') {
            return redirect()
                ->route('doctor.schedule')
                ->withErrors([
                    'slot' => 'Można edytować tylko wolny termin.',
                ]);
        }

        $doctor->load('clinics');

        return view('doctor.edit-schedule-slot', [
            'doctor' => $doctor,
            'slot' => $slot,
            'clinics' => $doctor->clinics,
        ]);
    }

    public function updateScheduleSlot(Request $request, AvailabilitySlot $slot)
    {
        $request->validate([
            'clinic_id' => ['required', 'exists:clinics,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor || $slot->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($slot->status !== 'available') {
            return redirect()
                ->route('doctor.schedule')
                ->withErrors([
                    'slot' => 'Można edytować tylko wolny termin. Termin zarezerwowany nie może zostać zmieniony.',
                ]);
        }

        $clinicBelongsToDoctor = $doctor->clinics()
            ->where('clinics.id', $request->clinic_id)
            ->exists();

        if (! $clinicBelongsToDoctor) {
            return back()->withErrors([
                'clinic_id' => 'Nie możesz przypisać terminu do kliniki, która nie jest przypisana do Twojego profilu.',
            ]);
        }

        $slotStart = \Carbon\Carbon::parse($request->date . ' ' . $request->start_time);
        $slotEnd = \Carbon\Carbon::parse($request->date . ' ' . $request->end_time);

        $exists = AvailabilitySlot::where('doctor_id', $doctor->id)
            ->where('id', '!=', $slot->id)
            ->where(function ($query) use ($slotStart, $slotEnd) {
                $query->where('start_time', '<', $slotEnd)
                    ->where('end_time', '>', $slotStart);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'slot' => 'Ten termin nakłada się na inny termin w grafiku.',
            ]);
        }

        $slot->update([
            'clinic_id' => $request->clinic_id,
            'start_time' => $slotStart,
            'end_time' => $slotEnd,
        ]);

        return redirect()
            ->route('doctor.schedule')
            ->with('success', 'Termin został zaktualizowany.');
    }

    public function deleteScheduleSlot(AvailabilitySlot $slot)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor || $slot->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($slot->status !== 'available') {
            return back()->withErrors([
                'slot' => 'Można usunąć tylko wolny termin. Termin zarezerwowany nie może zostać usunięty.',
            ]);
        }

        $slot->delete();

        return back()->with('success', 'Termin został usunięty z grafiku.');
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
                'availableSpecializations' => collect(),
                'helpTags' => collect(),
                'message' => 'Nie masz profilu lekarza.',
            ]);
        }

        $doctor->load([
            'specializations',
            'helpTags',
        ]);

        $availableSpecializations = DoctorSpecialization::query()
            ->select('specialization_name')
            ->distinct()
            ->orderBy('specialization_name')
            ->pluck('specialization_name');

        $helpTags = HelpTag::query()
            ->orderBy('tag_name')
            ->get();

        return view('doctor.profile', [
            'doctor' => $doctor,
            'availableSpecializations' => $availableSpecializations,
            'helpTags' => $helpTags,
            'message' => null,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'bio' => ['nullable', 'string', 'max:2000'],
            'is_for_adults' => ['nullable', 'boolean'],
            'is_for_children' => ['nullable', 'boolean'],
            'specializations' => ['nullable', 'array'],
            'specializations.*' => ['string', 'max:255'],
            'help_tags' => ['nullable', 'array'],
            'help_tags.*' => ['integer', 'exists:help_tags,id'],
        ]);

        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            abort(403);
        }

        DB::transaction(function () use ($request, $doctor) {
            $doctor->update([
                'bio' => $request->bio,
                'is_for_adults' => $request->boolean('is_for_adults'),
                'is_for_children' => $request->boolean('is_for_children'),
            ]);

            $doctor->specializations()->delete();

            foreach ($request->input('specializations', []) as $specializationName) {
                DoctorSpecialization::create([
                    'doctor_id' => $doctor->id,
                    'specialization_name' => $specializationName,
                ]);
            }

            $doctor->helpTags()->sync($request->input('help_tags', []));
        });

        return back()->with('success', 'Profil lekarza został zaktualizowany.');
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
