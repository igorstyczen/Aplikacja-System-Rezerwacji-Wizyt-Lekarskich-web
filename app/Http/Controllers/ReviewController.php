<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Image;
use App\Models\Patient;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function create(Appointment $appointment)
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        if (! $patient || $appointment->patient_id !== $patient->id) {
            abort(403);
        }

        if ($appointment->status !== 'completed') {
            return redirect()
                ->route('patient.appointments')
                ->withErrors([
                    'review' => 'Opinię można dodać dopiero po zakończonej wizycie.',
                ]);
        }

        $existingReview = Review::where('appointment_id', $appointment->id)->first();

        if ($existingReview) {
            return redirect()
                ->route('patient.appointments')
                ->withErrors([
                    'review' => 'Opinia dla tej wizyty została już dodana.',
                ]);
        }

        $appointment->load(['doctor', 'service', 'clinic']);

        return view('reviews.create', [
            'appointment' => $appointment,
        ]);
    }

    public function store(Request $request, Appointment $appointment)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $patient = Patient::where('user_id', Auth::id())->first();

        if (! $patient || $appointment->patient_id !== $patient->id) {
            abort(403);
        }

        if ($appointment->status !== 'completed') {
            return redirect()
                ->route('patient.appointments')
                ->withErrors([
                    'review' => 'Opinię można dodać dopiero po zakończonej wizycie.',
                ]);
        }

        $existingReview = Review::where('appointment_id', $appointment->id)->first();

        if ($existingReview) {
            return redirect()
                ->route('patient.appointments')
                ->withErrors([
                    'review' => 'Opinia dla tej wizyty została już dodana.',
                ]);
        }

        DB::transaction(function () use ($request, $appointment) {
            $review = Review::create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $appointment->doctor_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');

                $path = $file->store('review-images', 'public');

                $image = Image::create([
                    'image' => 'storage/' . $path,
                    'description' => 'Zdjęcie dodane do opinii',
                    'file_name' => basename($path),
                    'original_file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                ]);

                $review->images()->attach($image->id);
            }
        });

        return redirect()
            ->route('patient.appointments')
            ->with('success', 'Opinia została dodana.');
    }
}
