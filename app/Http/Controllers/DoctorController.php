<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function show(Request $request, Doctor $doctor)
    {
        $week = (int) $request->query('week', 0);

        if ($week < 0) {
            $week = 0;
        }

        if ($week > 4) {
            $week = 4;
        }

        /*
         * Tydzień kalendarza od poniedziałku do niedzieli.
         * week=0 to bieżący tydzień, week=1 następny itd.
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

        return view('doctors.show', [
            'doctor' => $doctor,
            'availabilitySlots' => $availabilitySlots,
            'week' => $week,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'weekDays' => $weekDays,
        ]);
    }
}
