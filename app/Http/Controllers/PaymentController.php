<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailabilitySlot;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show(Appointment $appointment)
    {
        $this->authorizePaymentAccess($appointment);

        if ($this->isPaymentExpired($appointment)) {
            $this->cancelExpiredPayment($appointment);

            return redirect()
                ->route('patient.appointments')
                ->withErrors([
                    'payment' => 'Czas na płatność minął. Termin został zwolniony i można zarezerwować go ponownie.',
                ]);
        }

        $appointment->load([
            'patient',
            'doctor',
            'service',
            'clinic',
        ]);

        return view('payments.show', [
            'appointment' => $appointment,
        ]);
    }

    public function pay(Request $request, Appointment $appointment)
    {
        $this->authorizePaymentAccess($appointment);

        if ($this->isPaymentExpired($appointment)) {
            $this->cancelExpiredPayment($appointment);

            return redirect()
                ->route('patient.appointments')
                ->withErrors([
                    'payment' => 'Czas na płatność minął. Termin został zwolniony i można zarezerwować go ponownie.',
                ]);
        }

        if ($appointment->status === 'cancelled') {
            return redirect()
                ->route('patient.appointments')
                ->withErrors([
                    'payment' => 'Nie można opłacić anulowanej wizyty.',
                ]);
        }

        if ($appointment->payment_status === 'paid') {
            return redirect()
                ->route('patient.appointments')
                ->with('success', 'Ta wizyta jest już opłacona.');
        }

        $request->validate([
            'payment_method' => ['required', 'in:blik,card'],
        ]);

        if ($request->payment_method === 'blik') {
            $request->validate([
                'blik_code' => ['required', 'digits:6'],
            ]);
        }

        if ($request->payment_method === 'card') {
            $request->validate([
                'card_number' => ['required', 'digits:16'],
                'card_expiry' => ['required', 'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'],
                'card_cvv' => ['required', 'digits:3'],
            ]);
        }

        $appointment->update([
            'status' => 'pending',
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'paid_at' => now(),
        ]);

        return redirect()
            ->route('patient.appointments')
            ->with('success', 'Płatność zakończona powodzeniem. Wizyta została opłacona i oczekuje na potwierdzenie lekarza.');
    }

    private function authorizePaymentAccess(Appointment $appointment): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        if ($user->role === 'admin') {
            return;
        }

        $patient = Patient::where('user_id', $user->id)->first();

        if (! $patient || $appointment->patient_id !== $patient->id) {
            abort(403);
        }
    }

    private function isPaymentExpired(Appointment $appointment): bool
    {
        return $appointment->status === 'pending_payment'
            && $appointment->payment_status === 'unpaid'
            && $appointment->created_at->lessThan(now()->subMinutes(10));
    }

    private function cancelExpiredPayment(Appointment $appointment): void
    {
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
    }
}
