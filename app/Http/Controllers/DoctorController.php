<?php

namespace App\Http\Controllers;

use App\Models\Doctor;

class DoctorController extends Controller
{
    public function show(Doctor $doctor)
    {
        $doctor->load([
            'specializations',
            'helpTags',
            'clinics',
            'services.clinic',
            'availabilitySlots.clinic',
            'reviews.appointment.patient',
            'reviews.images',
            'images',
        ]);

        $availableSlots = $doctor->availabilitySlots()
            ->with('clinic')
            ->where('status', 'available')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($slot) {
                return $slot->start_time->format('Y-m-d');
            });

        return view('doctors.show', [
            'doctor' => $doctor,
            'availableSlots' => $availableSlots,
        ]);
    }
}
