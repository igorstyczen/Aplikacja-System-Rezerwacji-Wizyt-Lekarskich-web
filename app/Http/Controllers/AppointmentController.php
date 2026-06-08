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

        $appointment = null;

        try {
            DB::transaction(function () use ($request, $patient, &$appointment) {
                $slot = AvailabilitySlot::where('id', $request->slot_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $expiredAppointment = Appointment::where('doctor_id', $slot->doctor_id)
                    ->where('clinic_id', $slot->clinic_id)
                    ->where('date', $slot->start_time)
                    ->where('status', 'pending_payment')
                    ->where('payment_status', 'unpaid')
                    ->where('created_at', '<', now()->subMinutes(10))
                    ->lockForUpdate()
                    ->first();

                if ($expiredAppointment) {
                    $expiredAppointment->update([
                        'status' => 'cancelled',
                    ]);

                    $slot->update([
                        'status' => 'available',
                    ]);
                }

                if ($slot->fresh()->status !== 'available') {
                    throw new \Exception('Ten termin jest już zajęty.');
                }

                $service = Service::where('id', $request->service_id)
                    ->where('doctor_id', $slot->doctor_id)
                    ->where('clinic_id', $slot->clinic_id)
                    ->first();

                if (! $service) {
                    throw new \Exception('Wybrana usługa nie pasuje do tego lekarza lub kliniki.');
                }

                $appointment = Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $slot->doctor_id,
                    'service_id' => $service->id,
                    'clinic_id' => $slot->clinic_id,
                    'date' => $slot->start_time,
                    'length' => $service->duration_minutes,
                    'status' => 'pending_payment',
                    'payment_status' => 'unpaid',
                    'payment_method' => null,
                    'payment_amount' => $service->price,
                    'paid_at' => null,
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

        return redirect()
            ->route('payments.show', $appointment)
            ->with('success', 'Termin został wstępnie zablokowany na 10 minut. Dokończ płatność, aby potwierdzić rezerwację.');
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
