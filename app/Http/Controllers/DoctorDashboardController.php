<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailabilitySlot;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorSpecialization;
use App\Models\HelpTag;
use App\Models\Service;
use App\Models\Specialization;
use App\Services\HelpTagSimilarityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class DoctorDashboardController extends Controller
{
    public function schedule()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            return view('doctor.schedule', [
                'doctor' => null,
                'slots' => new LengthAwarePaginator([], 0, 50),
                'clinics' => collect(),
                'message' => 'Nie masz profilu lekarza.',
            ]);
        }

        $clinics = Clinic::query()
            ->orderBy('city')
            ->orderBy('name')
            ->get();

        $slots = AvailabilitySlot::with('clinic')
            ->where('doctor_id', $doctor->id)
            ->orderBy('start_time')
            ->paginate(50)
            ->withQueryString();

        return view('doctor.schedule', [
            'doctor' => $doctor,
            'slots' => $slots,
            'clinics' => $clinics,
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
            'repeat_until' => ['nullable', 'date', 'after_or_equal:date', 'required_if:repeat_weekly,1,true,on,yes'],
        ]);

        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            abort(403);
        }

        $doctor->clinics()->syncWithoutDetaching([$request->clinic_id]);

        $baseDate = \Carbon\Carbon::parse($request->date)->startOfDay();
        $duration = (int) $request->slot_duration;
        $isRecurring = $request->boolean('repeat_weekly');
        $repeatUntil = $isRecurring
            ? \Carbon\Carbon::parse($request->repeat_until)->endOfDay()
            : null;

        if ($isRecurring && $repeatUntil->lt($baseDate)) {
            return back()->withErrors([
                'repeat_until' => 'Data końcowa powtarzania musi być późniejsza niż wybrany dzień.',
            ])->withInput();
        }

        $datesToCreate = [$baseDate->format('Y-m-d')];

        if ($isRecurring) {
            $datesToCreate = [];
            $occurrenceDate = $baseDate->copy();

            while ($occurrenceDate->lte($repeatUntil)) {
                $datesToCreate[] = $occurrenceDate->format('Y-m-d');
                $occurrenceDate->addWeek();
            }
        }

        $createdSlots = 0;
        $skippedSlots = 0;

        DB::transaction(function () use ($doctor, $request, $duration, $datesToCreate, $isRecurring, $repeatUntil, &$createdSlots, &$skippedSlots) {
            foreach ($datesToCreate as $date) {
                $start = \Carbon\Carbon::parse($date . ' ' . $request->start_time);
                $end = \Carbon\Carbon::parse($date . ' ' . $request->end_time);

                $current = $start->copy();

                while ($current->copy()->addMinutes($duration)->lessThanOrEqualTo($end)) {
                    $slotStart = $current->copy();
                    $slotEnd = $current->copy()->addMinutes($duration);

                    $exists = AvailabilitySlot::where('doctor_id', $doctor->id)
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
                            'is_recurring' => $isRecurring,
                            'recurrence_rule' => $isRecurring
                                ? 'weekly_until:' . $repeatUntil->format('Y-m-d')
                                : null,
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
            $message .= ' Terminy zostały powtórzone co tydzień przez ' . count($datesToCreate) . ' tygodni (do ' . \Carbon\Carbon::parse($request->repeat_until)->format('d.m.Y') . ').';
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

        $clinics = Clinic::query()
            ->orderBy('city')
            ->orderBy('name')
            ->get();

        return view('doctor.edit-schedule-slot', [
            'doctor' => $doctor,
            'slot' => $slot,
            'clinics' => $clinics,
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

        $doctor->clinics()->syncWithoutDetaching([$request->clinic_id]);

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

    public function appointments(Request $request)
    {
        $sort = $request->query('sort', 'desc');

        if (! in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'desc';
        }

        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            return view('doctor.appointments', [
                'doctor' => null,
                'appointments' => collect(),
                'message' => 'Nie masz profilu lekarza.',
                'sort' => $sort,
            ]);
        }

        $appointments = Appointment::with([
                'patient',
                'service',
                'clinic',
            ])
            ->where('doctor_id', $doctor->id)
            ->orderBy('date', $sort)
            ->get();

        return view('doctor.appointments', [
            'doctor' => $doctor,
            'appointments' => $appointments,
            'message' => null,
            'sort' => $sort,
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

    public function services()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            return view('doctor.services', [
                'doctor' => null,
                'services' => collect(),
                'clinics' => collect(),
                'message' => 'Nie masz profilu lekarza.',
            ]);
        }

        $clinics = Clinic::query()
            ->orderBy('city')
            ->orderBy('name')
            ->get();

        $services = Service::with('clinic')
            ->where('doctor_id', $doctor->id)
            ->orderBy('name')
            ->get();

        return view('doctor.services', [
            'doctor' => $doctor,
            'services' => $services,
            'clinics' => $clinics,
            'message' => null,
        ]);
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'clinic_id' => ['required', 'exists:clinics,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999'],
            'duration_minutes' => ['required', 'integer', 'min:10', 'max:240'],
        ]);

        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            abort(403);
        }

        $doctor->clinics()->syncWithoutDetaching([$request->clinic_id]);

        Service::create([
            'doctor_id' => $doctor->id,
            'clinic_id' => $request->clinic_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration_minutes' => $request->duration_minutes,
        ]);

        return back()->with('success', 'Usługa została dodana.');
    }

    public function updateService(Request $request, Service $service)
    {
        $request->validate([
            'clinic_id' => ['required', 'exists:clinics,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999'],
            'duration_minutes' => ['required', 'integer', 'min:10', 'max:240'],
        ]);

        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor || $service->doctor_id !== $doctor->id) {
            abort(403);
        }

        $doctor->clinics()->syncWithoutDetaching([$request->clinic_id]);

        $service->update([
            'clinic_id' => $request->clinic_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration_minutes' => $request->duration_minutes,
        ]);

        return back()->with('success', 'Usługa została zaktualizowana.');
    }

    public function deleteService(Service $service)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor || $service->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($service->appointments()->exists()) {
            return back()->withErrors([
                'service' => 'Nie można usunąć usługi, która została już użyta w wizycie.',
            ]);
        }

        $service->delete();

        return back()->with('success', 'Usługa została usunięta.');
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

        $availableSpecializations = Specialization::query()
            ->orderBy('name')
            ->get();

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
            'specializations.*' => ['integer', 'exists:specializations,id'],
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

        return back()->with('success', 'Profil lekarza został zaktualizowany.');
    }

    public function storeHelpTag(Request $request, HelpTagSimilarityService $similarityService)
    {
        $request->validate([
            'tag_name' => ['required', 'string', 'max:100'],
        ]);

        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (! $doctor) {
            abort(403);
        }

        $tagName = trim($request->tag_name);

        $existingTag = HelpTag::where('tag_name', $tagName)->first();

        if ($existingTag) {
            $doctor->helpTags()->syncWithoutDetaching([$existingTag->id]);

            return back()->with('success', 'Tag „' . $existingTag->tag_name . '” został przypisany do Twojego profilu.');
        }

        $similarTags = $similarityService->findSimilar($tagName, 0.75);

        if ($similarTags->isNotEmpty()) {
            $suggestions = $similarTags->pluck('tag_name')->take(3)->implode(', ');

            return back()->withErrors([
                'tag_name' => 'Podobny tag już istnieje w systemie: ' . $suggestions . '. Wybierz go z listy lub użyj innej nazwy.',
            ]);
        }

        $tag = HelpTag::create([
            'tag_name' => $tagName,
        ]);

        $doctor->helpTags()->attach($tag->id);

        return back()->with('success', 'Nowy tag „' . $tag->tag_name . '” został dodany i przypisany do profilu.');
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

        if ($doctor->photo_url) {
            $previousPath = preg_replace('#^storage/#', '', $doctor->photo_url);
            Storage::disk('public')->delete($previousPath);
        }

        $doctor->update([
            'photo_url' => 'storage/' . $path,
        ]);

        return back()->with('success', 'Zdjęcie profilowe zostało zaktualizowane.');
    }
}
