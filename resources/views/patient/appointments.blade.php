<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Moje wizyty
        </h2>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1280px; margin: 0 auto;">

            @if (session('success'))
                <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 0 0 6px 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if ($message)
                <div style="background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    {{ $message }}
                </div>
            @endif

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #2563eb; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                    Panel pacjenta
                </p>

                <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                    Moje wizyty
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 850px;">
                    Tutaj widzisz swoje rezerwacje, status wizyty, informacje o płatności oraz dostępne akcje,
                    takie jak opłacenie wizyty, anulowanie lub dodanie opinii po zakończonej wizycie.
                </p>
            </div>

            @if ($appointments->count() > 0)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <div style="padding: 26px 30px; border-bottom: 1px solid #e5e7eb;">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Lista wizyt
                        </h2>

                        <p style="font-size: 14px; color: #6b7280;">
                            Sprawdź datę wizyty, lekarza, usługę, klinikę, płatność oraz aktualny status.
                        </p>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 1050px;">
                            <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Data</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Lekarz</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Usługa</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Klinika</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Status</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Płatność</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Akcje</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($appointments as $appointment)
                                    @php
                                        $hasReview = \App\Models\Review::where('appointment_id', $appointment->id)->exists();
                                    @endphp

                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 18px; font-size: 14px; color: #111827; font-weight: 800; white-space: nowrap;">
                                            {{ $appointment->date->format('d.m.Y') }}
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280; font-weight: 600;">
                                                {{ $appointment->date->format('H:i') }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            <strong>
                                                Dr {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                                            </strong>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            <strong>{{ $appointment->service->name }}</strong>
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280;">
                                                {{ $appointment->length }} min
                                            </span>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            <strong>{{ $appointment->clinic->name }}</strong>
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280;">
                                                {{ $appointment->clinic->city }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            @if ($appointment->status === 'pending_payment')
                                                <span style="padding: 6px 10px; background: #ffedd5; color: #c2410c; border-radius: 999px; font-size: 12px; font-weight: 900; white-space: nowrap;">
                                                    Oczekuje na płatność
                                                </span>
                                            @elseif ($appointment->status === 'cancelled')
                                                <span style="padding: 6px 10px; background: #fee2e2; color: #991b1b; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Anulowana
                                                </span>
                                            @elseif ($appointment->status === 'pending')
                                                <span style="padding: 6px 10px; background: #dbeafe; color: #1d4ed8; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Oczekująca
                                                </span>
                                            @elseif ($appointment->status === 'confirmed')
                                                <span style="padding: 6px 10px; background: #fef3c7; color: #92400e; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Potwierdzona
                                                </span>
                                            @elseif ($appointment->status === 'completed')
                                                <span style="padding: 6px 10px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Zakończona
                                                </span>
                                            @else
                                                <span style="padding: 6px 10px; background: #f3f4f6; color: #374151; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    {{ $appointment->status }}
                                                </span>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            @if ($appointment->payment_status === 'paid')
                                                <span style="padding: 6px 10px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Opłacona
                                                </span>

                                                @if ($appointment->payment_method)
                                                    <br>
                                                    <span style="display: inline-block; margin-top: 6px; font-size: 12px; color: #6b7280;">
                                                        @if ($appointment->payment_method === 'blik')
                                                            BLIK
                                                        @elseif ($appointment->payment_method === 'card')
                                                            Karta bankowa
                                                        @else
                                                            {{ $appointment->payment_method }}
                                                        @endif
                                                    </span>
                                                @endif
                                            @else
                                                <span style="padding: 6px 10px; background: #fee2e2; color: #991b1b; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Nieopłacona
                                                </span>
                                                <br>
                                                <span style="display: inline-block; margin-top: 6px; font-size: 12px; color: #6b7280;">
                                                    {{ number_format($appointment->payment_amount ?? $appointment->service->price, 2) }} zł
                                                </span>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            <div style="display: flex; flex-direction: column; gap: 8px; min-width: 145px;">
                                                @if ($appointment->status === 'pending_payment' && $appointment->payment_status !== 'paid')
                                                    <a
                                                        href="{{ route('payments.show', $appointment) }}"
                                                        style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 8px 12px; background: #dcfce7; color: #166534; border-radius: 9px; font-size: 12px; font-weight: 900; text-decoration: none;"
                                                    >
                                                        Opłać
                                                    </a>
                                                @endif

                                                @if (! in_array($appointment->status, ['cancelled', 'completed']))
                                                    <form method="POST" action="{{ route('appointments.cancel', $appointment) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            onclick="return confirm('Czy na pewno chcesz anulować tę wizytę?')"
                                                            style="width: 100%; padding: 8px 12px; background: #fee2e2; color: #b91c1c; border-radius: 9px; border: none; font-size: 12px; font-weight: 900; cursor: pointer;"
                                                        >
                                                            Anuluj
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($appointment->status === 'completed' && ! $hasReview)
                                                    <a
                                                        href="{{ route('reviews.create', $appointment) }}"
                                                        style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 8px 12px; background: #dbeafe; color: #1d4ed8; border-radius: 9px; font-size: 12px; font-weight: 900; text-decoration: none;"
                                                    >
                                                        Dodaj opinię
                                                    </a>
                                                @endif

                                                @if ($appointment->status === 'completed' && $hasReview)
                                                    <span style="font-size: 12px; color: #9ca3af; font-weight: 700;">
                                                        Opinia dodana
                                                    </span>
                                                @endif

                                                @if ($appointment->status === 'cancelled')
                                                    <span style="font-size: 12px; color: #9ca3af; font-weight: 700;">
                                                        Brak akcji
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <p style="color: #6b7280;">
                        Nie masz jeszcze żadnych wizyt.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
