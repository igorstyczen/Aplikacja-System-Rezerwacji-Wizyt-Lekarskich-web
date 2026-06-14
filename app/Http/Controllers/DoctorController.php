<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function show(Request $request, Doctor $doctor)
    {
        if (! $request->has('week')) {
            $defaultWeek = max(0, $this->resolveDefaultWeek($doctor));

            return redirect()->route('doctors.show', [
                'doctor' => $doctor,
                'week' => $defaultWeek,
            ]);
        }

        $week = (int) $request->query('week', 0);

        if ($week < 0) {
            $week = 0;
        }

        $maxWeek = $this->resolveMaxWeek($doctor);

        if ($week > $maxWeek) {
            $week = $maxWeek;
        }

        /*
         * Tydzień kalendarza od poniedziałku do niedzieli.
         * week=0 to bieżący tydzień kalendarzowy, week=1 następny itd.
         */
        $weekStart = Carbon::today()->startOfWeek(Carbon::MONDAY)->addWeeks($week)->startOfDay();
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();

        $weekDays = collect(range(0, 6))->map(function (int $offset) use ($weekStart) {
            return $weekStart->copy()->addDays($offset)->startOfDay();
        });

        $doctor->load([
            'specializations',
            'helpTags',
            'clinics',
            'services.clinic',
            'reviews.appointment.patient',
            'reviews.images',
            'images',
        ]);

        $availabilitySlots = $doctor->availabilitySlots()
            ->with('clinic')
            ->where('status', 'available')
            ->where('start_time', '>=', now())
            ->whereBetween('start_time', [
                $weekStart->copy()->startOfDay(),
                $weekEnd->copy()->endOfDay(),
            ])
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($slot) {
                return $slot->start_time->format('Y-m-d');
            });

        $slotsInWeekCount = $availabilitySlots->flatten()->count();

        return view('doctors.show', [
            'doctor' => $doctor,
            'availabilitySlots' => $availabilitySlots,
            'week' => $week,
            'maxWeek' => $maxWeek,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'weekDays' => $weekDays,
            'slotsInWeekCount' => $slotsInWeekCount,
        ]);
    }

    private function resolveMaxWeek(Doctor $doctor): int
    {
        $lastSlotStart = $doctor->availabilitySlots()
            ->where('status', 'available')
            ->where('start_time', '>=', now())
            ->orderByDesc('start_time')
            ->value('start_time');

        if (! $lastSlotStart) {
            return 0;
        }

        $currentWeekStart = Carbon::today()->startOfWeek(Carbon::MONDAY);
        $lastWeekStart = Carbon::parse($lastSlotStart)->startOfWeek(Carbon::MONDAY);

        if ($lastWeekStart->lt($currentWeekStart)) {
            return 0;
        }

        return (int) $currentWeekStart->diffInWeeks($lastWeekStart);
    }

    private function resolveDefaultWeek(Doctor $doctor): int
    {
        $maxWeek = $this->resolveMaxWeek($doctor);

        for ($week = 0; $week <= $maxWeek; $week++) {
            $weekStart = Carbon::today()->startOfWeek(Carbon::MONDAY)->addWeeks($week)->startOfDay();
            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();

            $hasSlots = $doctor->availabilitySlots()
                ->where('status', 'available')
                ->where('start_time', '>=', now())
                ->whereBetween('start_time', [$weekStart, $weekEnd])
                ->exists();

            if ($hasSlots) {
                return $week;
            }
        }

        return 0;
    }
}
