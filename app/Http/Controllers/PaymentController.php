<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function show(Appointment $appointment)
    {
        $this->authorizePaymentAccess($appointment);

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

        if ($appointment->status === 'cancelled') {
            return back()->withErrors([
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
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'paid_at' => now(),
        ]);

        return redirect()
            ->route('patient.appointments')
            ->with('success', 'Płatność testowa zakończona powodzeniem. Wizyta została opłacona.');
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
}
