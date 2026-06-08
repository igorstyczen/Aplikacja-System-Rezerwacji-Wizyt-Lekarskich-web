<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel administratora
        </h2>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1280px; margin: 0 auto;">

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #2563eb; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                    Panel administratora
                </p>

                <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 12px;">
                    Centrum zarządzania systemem
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 850px;">
                    Szybki dostęp do użytkowników, lekarzy, klinik, wizyt, zgłoszeń lekarzy oraz słowników specjalizacji i tagów pomocy.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 16px; margin-bottom: 28px;">
                <a href="{{ route('admin.doctor-applications') }}" style="display: block; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 18px; padding: 18px; text-decoration: none;">
                    <p style="font-size: 14px; font-weight: 900; color: #047857; margin-bottom: 6px;">
                        Zgłoszenia lekarzy
                    </p>
                    <p style="font-size: 12px; color: #065f46; line-height: 1.5;">
                        Akceptacja nowych profili.
                    </p>
                </a>

                <a href="{{ route('admin.doctors.create') }}" style="display: block; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 18px; padding: 18px; text-decoration: none;">
                    <p style="font-size: 14px; font-weight: 900; color: #1d4ed8; margin-bottom: 6px;">
                        Dodaj lekarza
                    </p>
                    <p style="font-size: 12px; color: #1e40af; line-height: 1.5;">
                        Konto i profil lekarza.
                    </p>
                </a>

                <a href="{{ route('admin.appointments') }}" style="display: block; background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 18px; padding: 18px; text-decoration: none;">
                    <p style="font-size: 14px; font-weight: 900; color: #6d28d9; margin-bottom: 6px;">
                        Wizyty
                    </p>
                    <p style="font-size: 12px; color: #5b21b6; line-height: 1.5;">
                        Statusy i płatności.
                    </p>
                </a>

                <a href="{{ route('admin.clinics') }}" style="display: block; background: #ecfeff; border: 1px solid #a5f3fc; border-radius: 18px; padding: 18px; text-decoration: none;">
                    <p style="font-size: 14px; font-weight: 900; color: #0e7490; margin-bottom: 6px;">
                        Kliniki
                    </p>
                    <p style="font-size: 12px; color: #155e75; line-height: 1.5;">
                        Placówki i lekarze.
                    </p>
                </a>

                <a href="{{ route('admin.specializations') }}" style="display: block; background: #fff7ed; border: 1px solid #fed7aa; border-radius: 18px; padding: 18px; text-decoration: none;">
                    <p style="font-size: 14px; font-weight: 900; color: #c2410c; margin-bottom: 6px;">
                        Specjalizacje
                    </p>
                    <p style="font-size: 12px; color: #9a3412; line-height: 1.5;">
                        Słownik specjalizacji.
                    </p>
                </a>

                <a href="{{ route('admin.help-tags') }}" style="display: block; background: #fdf2f8; border: 1px solid #fbcfe8; border-radius: 18px; padding: 18px; text-decoration: none;">
                    <p style="font-size: 14px; font-weight: 900; color: #be185d; margin-bottom: 6px;">
                        Tagi pomocy
                    </p>
                    <p style="font-size: 12px; color: #9d174d; line-height: 1.5;">
                        Obszary problemów.
                    </p>
                </a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 18px; margin-bottom: 28px;">
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 20px; padding: 24px; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);">
                    <p style="font-size: 13px; font-weight: 800; color: #6b7280; margin-bottom: 8px;">
                        Użytkownicy
                    </p>
                    <p style="font-size: 34px; font-weight: 900; color: #111827;">
                        {{ $usersCount }}
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 20px; padding: 24px; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);">
                    <p style="font-size: 13px; font-weight: 800; color: #6b7280; margin-bottom: 8px;">
                        Lekarze
                    </p>
                    <p style="font-size: 34px; font-weight: 900; color: #111827;">
                        {{ $doctorsCount }}
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 20px; padding: 24px; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);">
                    <p style="font-size: 13px; font-weight: 800; color: #6b7280; margin-bottom: 8px;">
                        Pacjenci
                    </p>
                    <p style="font-size: 34px; font-weight: 900; color: #111827;">
                        {{ $patientsCount }}
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 20px; padding: 24px; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);">
                    <p style="font-size: 13px; font-weight: 800; color: #6b7280; margin-bottom: 8px;">
                        Wizyty
                    </p>
                    <p style="font-size: 34px; font-weight: 900; color: #111827;">
                        {{ $appointmentsCount }}
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 18px; margin-bottom: 28px;">
                <div style="background: #fff7ed; border: 1px solid #fed7aa; padding: 22px; border-radius: 20px;">
                    <p style="font-size: 13px; font-weight: 800; color: #c2410c; margin-bottom: 8px;">
                        Oczekuje na płatność
                    </p>
                    <p style="font-size: 30px; font-weight: 900; color: #9a3412;">
                        {{ $pendingPaymentAppointmentsCount }}
                    </p>
                </div>

                <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 22px; border-radius: 20px;">
                    <p style="font-size: 13px; font-weight: 800; color: #1d4ed8; margin-bottom: 8px;">
                        Oczekujące
                    </p>
                    <p style="font-size: 30px; font-weight: 900; color: #1e40af;">
                        {{ $pendingAppointmentsCount }}
                    </p>
                </div>

                <div style="background: #fefce8; border: 1px solid #fde68a; padding: 22px; border-radius: 20px;">
                    <p style="font-size: 13px; font-weight: 800; color: #a16207; margin-bottom: 8px;">
                        Potwierdzone
                    </p>
                    <p style="font-size: 30px; font-weight: 900; color: #854d0e;">
                        {{ $confirmedAppointmentsCount }}
                    </p>
                </div>

                <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 22px; border-radius: 20px;">
                    <p style="font-size: 13px; font-weight: 800; color: #b91c1c; margin-bottom: 8px;">
                        Anulowane
                    </p>
                    <p style="font-size: 30px; font-weight: 900; color: #991b1b;">
                        {{ $cancelledAppointmentsCount }}
                    </p>
                </div>

                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 22px; border-radius: 20px;">
                    <p style="font-size: 13px; font-weight: 800; color: #15803d; margin-bottom: 8px;">
                        Zakończone
                    </p>
                    <p style="font-size: 30px; font-weight: 900; color: #166534;">
                        {{ $completedAppointmentsCount }}
                    </p>
                </div>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <div style="padding: 26px 30px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; gap: 16px;">
                    <div>
                        <h3 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Ostatnie wizyty
                        </h3>

                        <p style="font-size: 14px; color: #6b7280;">
                            Najnowsze rezerwacje zapisane w systemie.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.appointments') }}"
                        style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 18px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 10px; text-decoration: none;"
                    >
                        Zobacz wszystkie
                    </a>
                </div>

                @if ($latestAppointments->count() > 0)
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; min-width: 1050px; border-collapse: collapse;">
                            <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Data</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Pacjent</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Lekarz</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Usługa</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Klinika</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Status</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Płatność</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($latestAppointments as $appointment)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 18px; font-size: 14px; color: #111827; font-weight: 800; white-space: nowrap;">
                                            {{ $appointment->date->format('d.m.Y') }}
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280;">
                                                {{ $appointment->date->format('H:i') }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            Dr {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            {{ $appointment->service->name }}
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            {{ $appointment->clinic->name }}
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            @if ($appointment->status === 'pending_payment')
                                                <span style="padding: 6px 10px; background: #ffedd5; color: #c2410c; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Oczekuje na płatność
                                                </span>
                                            @elseif ($appointment->status === 'pending')
                                                <span style="padding: 6px 10px; background: #dbeafe; color: #1d4ed8; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Oczekująca
                                                </span>
                                            @elseif ($appointment->status === 'confirmed')
                                                <span style="padding: 6px 10px; background: #fef3c7; color: #92400e; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Potwierdzona
                                                </span>
                                            @elseif ($appointment->status === 'cancelled')
                                                <span style="padding: 6px 10px; background: #fee2e2; color: #991b1b; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Anulowana
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
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="padding: 32px;">
                        <p style="color: #6b7280;">
                            Brak wizyt do wyświetlenia.
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
