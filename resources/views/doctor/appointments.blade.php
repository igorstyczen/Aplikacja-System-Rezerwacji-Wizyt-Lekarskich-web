<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Wizyty pacjentów
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

            @if ($doctor)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                    <p style="color: #059669; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                        Panel lekarza
                    </p>

                    <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                        Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </h1>

                    <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 850px;">
                        Tutaj widzisz wizyty pacjentów przypisane do Twojego profilu lekarza.
                        Możesz potwierdzać opłacone wizyty oraz oznaczać potwierdzone wizyty jako zakończone.
                    </p>
                </div>
            @endif

            @php
                $sort = $sort ?? request('sort', 'desc');
                if (! in_array($sort, ['asc', 'desc'], true)) {
                    $sort = 'desc';
                }
            @endphp

            @if ($doctor)
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 18px; padding: 18px 22px; margin-bottom: 24px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                    <span style="font-size: 14px; font-weight: 900; color: #047857; white-space: nowrap;">
                        Sortowanie po dacie wizyty:
                    </span>

                    <a
                        href="{{ route('doctor.appointments', ['sort' => 'desc']) }}"
                        style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 12px; font-size: 14px; font-weight: 900; text-decoration: none; border: 2px solid {{ $sort === 'desc' ? '#059669' : '#d1d5db' }}; background: {{ $sort === 'desc' ? '#059669' : 'white' }}; color: {{ $sort === 'desc' ? 'white' : '#374151' }};"
                    >
                        ↓ Od najnowszej
                    </a>

                    <a
                        href="{{ route('doctor.appointments', ['sort' => 'asc']) }}"
                        style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 12px; font-size: 14px; font-weight: 900; text-decoration: none; border: 2px solid {{ $sort === 'asc' ? '#059669' : '#d1d5db' }}; background: {{ $sort === 'asc' ? '#059669' : 'white' }}; color: {{ $sort === 'asc' ? 'white' : '#374151' }};"
                    >
                        ↑ Od najstarszej
                    </a>
                </div>
            @endif

            @if ($appointments->count() > 0)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <div style="padding: 26px 30px; border-bottom: 1px solid #e5e7eb;">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Lista wizyt pacjentów
                        </h2>

                        <p style="font-size: 14px; color: #6b7280; margin: 0;">
                            Statusy wizyt oraz informacje o płatnościach.
                        </p>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 1050px;">
                            <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        <a
                                            href="{{ route('doctor.appointments', ['sort' => $sort === 'desc' ? 'asc' : 'desc']) }}"
                                            style="display: inline-flex; align-items: center; gap: 6px; color: #059669; text-decoration: none;"
                                            title="Kliknij, aby zmienić kolejność sortowania"
                                        >
                                            Data
                                            <span style="font-size: 14px; line-height: 1;">
                                                {{ $sort === 'desc' ? '↓' : '↑' }}
                                            </span>
                                        </a>
                                    </th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Pacjent
                                    </th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Usługa
                                    </th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Klinika
                                    </th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Status
                                    </th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Płatność
                                    </th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Akcje
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($appointments as $appointment)
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
                                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                            </strong>
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280;">
                                                {{ $appointment->patient->phone ?? 'Brak telefonu' }}
                                            </span>
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

                                        <td style="padding: 18px; font-size: 14px;">
                                            <div style="display: flex; flex-direction: column; gap: 8px; min-width: 150px;">
                                                @if ($appointment->status === 'pending' && $appointment->payment_status === 'paid')
                                                    <form method="POST" action="{{ route('doctor.appointments.confirm', $appointment) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            style="width: 100%; padding: 8px 12px; background: #dbeafe; color: #1d4ed8; border-radius: 9px; border: none; font-size: 12px; font-weight: 900; cursor: pointer;"
                                                        >
                                                            Potwierdź
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($appointment->status === 'confirmed')
                                                    <form method="POST" action="{{ route('doctor.appointments.complete', $appointment) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            style="width: 100%; padding: 8px 12px; background: #dcfce7; color: #166534; border-radius: 9px; border: none; font-size: 12px; font-weight: 900; cursor: pointer;"
                                                        >
                                                            Zakończ
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($appointment->status === 'pending_payment')
                                                    <span style="font-size: 12px; color: #9ca3af; font-weight: 700;">
                                                        Czeka na płatność pacjenta
                                                    </span>
                                                @endif

                                                @if (in_array($appointment->status, ['cancelled', 'completed']))
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
                        Nie masz jeszcze żadnych wizyt pacjentów.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
