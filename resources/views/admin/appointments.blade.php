<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Wszystkie wizyty
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

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #2563eb; font-size: 14px; font-weight: 800; margin-bottom: 8px;">
                    Panel administratora
                </p>

                <h1 style="font-size: 30px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                    Lista wszystkich wizyt
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7;">
                    Administrator widzi wszystkie wizyty zapisane w systemie, może je filtrować,
                    sprawdzać płatności oraz zarządzać statusem wizyt.
                </p>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Filtry
                </h2>

                <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
                    Wyszukaj wizyty po pacjencie, lekarzu, klinice, statusie lub dacie.
                </p>

                <form method="GET" action="{{ route('admin.appointments') }}" style="display: flex; flex-direction: column; gap: 22px;">
                    <div style="display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 20px;">
                        <div>
                            <label for="patient" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Pacjent
                            </label>

                            <input
                                type="text"
                                id="patient"
                                name="patient"
                                value="{{ request('patient') }}"
                                placeholder="np. Piotr"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="doctor" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Lekarz
                            </label>

                            <input
                                type="text"
                                id="doctor"
                                name="doctor"
                                value="{{ request('doctor') }}"
                                placeholder="np. Jan"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="clinic" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Klinika / miasto
                            </label>

                            <input
                                type="text"
                                id="clinic"
                                name="clinic"
                                value="{{ request('clinic') }}"
                                placeholder="np. Rzeszów"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="status" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                                <option value="">Wszystkie</option>
                                <option value="pending_payment" @selected(request('status') === 'pending_payment')>
                                    Oczekuje na płatność
                                </option>
                                <option value="pending" @selected(request('status') === 'pending')>
                                    Oczekująca
                                </option>
                                <option value="confirmed" @selected(request('status') === 'confirmed')>
                                    Potwierdzona
                                </option>
                                <option value="cancelled" @selected(request('status') === 'cancelled')>
                                    Anulowana
                                </option>
                                <option value="completed" @selected(request('status') === 'completed')>
                                    Zakończona
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="date_from" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Data wizyty od
                            </label>

                            <input
                                type="date"
                                id="date_from"
                                name="date_from"
                                value="{{ request('date_from') }}"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 10px 14px; font-size: 14px;"
                            >
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 18px; padding-top: 4px;">
                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 24px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 10px; border: none; cursor: pointer;"
                        >
                            Filtruj
                        </button>

                        <a
                            href="{{ route('admin.appointments') }}"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 24px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 10px; text-decoration: none;"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            @if ($appointments->count() > 0)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 1150px;">
                            <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Data</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Pacjent</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Lekarz</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Usługa</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Klinika</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Czas</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Status</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Płatność</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Akcje</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($appointments as $appointment)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 18px; font-size: 14px; color: #111827; font-weight: 700; white-space: nowrap;">
                                            {{ $appointment->date->format('d.m.Y') }}
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280; font-weight: 600;">
                                                {{ $appointment->date->format('H:i') }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            <strong>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</strong>
                                            @if ($appointment->patient->phone)
                                                <br>
                                                <span style="font-size: 12px; color: #6b7280;">
                                                    {{ $appointment->patient->phone }}
                                                </span>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            Dr {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            {{ $appointment->service->name }}
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            {{ $appointment->clinic->name }}
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280;">
                                                {{ $appointment->clinic->city }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151; white-space: nowrap;">
                                            {{ $appointment->length }} min
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
                                                <br>
                                                <span style="display: inline-block; margin-top: 6px; font-size: 12px; color: #6b7280;">
                                                    {{ number_format($appointment->payment_amount ?? $appointment->service->price, 2) }} zł
                                                </span>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            <div style="display: flex; flex-direction: column; gap: 8px; min-width: 135px;">
                                                @if ($appointment->status === 'pending' && $appointment->payment_status === 'paid')
                                                    <form method="POST" action="{{ route('admin.appointments.confirm', $appointment) }}">
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
                                                    <form method="POST" action="{{ route('admin.appointments.complete', $appointment) }}">
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

                                                @if (! in_array($appointment->status, ['cancelled', 'completed']))
                                                    <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}">
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

                                                @if ($appointment->status === 'pending_payment')
                                                    <span style="font-size: 12px; color: #9ca3af; font-weight: 700;">
                                                        Czeka na płatność
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

                <div style="margin-top: 28px;">
                    {{ $appointments->links() }}
                </div>
            @else
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <p style="color: #6b7280;">
                        Brak wizyt spełniających wybrane filtry.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
