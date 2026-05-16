<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailabilitySlot;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'slot_id' => ['required', 'exists:availability_slots,id'],
            'service_id' => ['required', 'exists:services,id'],
        ]);

        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $patient = Patient::where('user_id', $user->id)->first();

        if (! $patient) {
            return back()->withErrors([
                'patient' => 'Nie masz jeszcze profilu pacjenta. Utwórz profil pacjenta, aby umówić wizytę.',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $patient) {
                $slot = AvailabilitySlot::where('id', $request->slot_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($slot->status !== 'available') {
                    throw new \Exception('Ten termin jest już zajęty.');
                }

                $service = Service::where('id', $request->service_id)
                    ->where('doctor_id', $slot->doctor_id)
                    ->where('clinic_id', $slot->clinic_id)
                    ->first();

                if (! $service) {
                    throw new \Exception('Wybrana usługa nie pasuje do tego lekarza lub kliniki.');
                }

                Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $slot->doctor_id,
                    'service_id' => $service->id,
                    'clinic_id' => $slot->clinic_id,
                    'date' => $slot->start_time,
                    'length' => $service->duration_minutes,
                    'status' => 'pending',
                ]);

                $slot->update([
                    'status' => 'booked',
                ]);
            });
        } catch (\Exception $e) {
            return back()->withErrors([
                'booking' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Wizyta została zarezerwowana.');
    }

    public function cancel(Appointment $appointment)
    {
        $user = Auth::user();

        $patient = Patient::where('user_id', $user->id)->first();

        if (! $patient || $appointment->patient_id !== $patient->id) {
            abort(403);
        }

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
}
