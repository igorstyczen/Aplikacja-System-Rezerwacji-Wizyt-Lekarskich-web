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

        $weekStart = Carbon::today()
            ->startOfWeek(Carbon::MONDAY)
            ->addWeeks($week);

        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

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
        ]);
    }
}
