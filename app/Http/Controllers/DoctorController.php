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

        return view('doctors.show', compact('doctor'));
    }
}
