<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kliniki
        </h2>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1180px; margin: 0 auto;">

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
                    Zarządzanie klinikami
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7;">
                    Administrator dodaje kliniki i przypisuje do nich wielu lekarzy.
                    Lekarz później wybiera przypisane kliniki przy tworzeniu usług oraz grafiku.
                </p>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 24px;">
                    <div>
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Dodaj klinikę
                        </h2>

                        <p style="color: #6b7280; font-size: 14px; margin: 0;">
                            Dodaj nową placówkę i opcjonalnie przypisz do niej lekarzy.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.clinics.store') }}" style="display: flex; flex-direction: column; gap: 24px;">
                    @csrf

                    <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Nazwa kliniki
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="np. Klinika Ortopedyczna"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Miasto
                            </label>

                            <input
                                type="text"
                                name="city"
                                value="{{ old('city') }}"
                                placeholder="np. Kraków"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Adres
                            </label>

                            <input
                                type="text"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="np. Jana Dekerta 18/2"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                            Szczegóły
                        </label>

                        <textarea
                            name="details"
                            rows="3"
                            placeholder="Dodatkowe informacje o klinice."
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 14px; font-size: 14px; resize: vertical;"
                        >{{ old('details') }}</textarea>
                    </div>

                    <div>
                        <h3 style="font-size: 14px; font-weight: 800; color: #374151; margin-bottom: 12px;">
                            Przypisz lekarzy do kliniki
                        </h3>

                        @if ($doctors->count() > 0)
                            <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px;">
                                @foreach ($doctors as $doctor)
                                    <label style="display: flex; align-items: center; gap: 10px; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px 14px; background: #f9fafb; cursor: pointer;">
                                        <input
                                            type="checkbox"
                                            name="doctors[]"
                                            value="{{ $doctor->id }}"
                                            @checked(in_array($doctor->id, old('doctors', [])))
                                        >

                                        <span style="font-size: 14px; font-weight: 700; color: #374151;">
                                            Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p style="font-size: 14px; color: #6b7280;">
                                Brak lekarzy w systemie.
                            </p>
                        @endif
                    </div>

                    <div style="display: flex; align-items: center; gap: 18px; padding-top: 4px;">
                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 8px 16px rgba(37, 99, 235, 0.18);"
                        >
                            Dodaj klinikę
                        </button>
                    </div>
                </form>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Filtry
                </h2>

                <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
                    Wyszukaj klinikę po nazwie, mieście lub przypisanym lekarzu.
                </p>

                <form method="GET" action="{{ route('admin.clinics') }}" style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Nazwa kliniki
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ request('name') }}"
                                placeholder="np. Przychodnia"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Miasto
                            </label>

                            <input
                                type="text"
                                name="city"
                                value="{{ request('city') }}"
                                placeholder="np. Rzeszów"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Lekarz
                            </label>

                            <input
                                type="text"
                                name="doctor"
                                value="{{ request('doctor') }}"
                                placeholder="np. Kowalski"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 18px;">
                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 22px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 10px; border: none; cursor: pointer;"
                        >
                            Filtruj
                        </button>

                        <a
                            href="{{ route('admin.clinics') }}"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 22px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 10px; text-decoration: none;"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            @if ($clinics->count() > 0)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; overflow: hidden; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <div style="padding: 26px 30px; border-bottom: 1px solid #e5e7eb;">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Lista klinik
                        </h2>

                        <p style="font-size: 14px; color: #6b7280;">
                            Wyszukaj klinikę i kliknij „Edytuj”, aby przejść do osobnej strony edycji.
                        </p>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                            <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Nazwa</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Miasto</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Adres</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Lekarze</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clinics as $clinic)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 18px; font-size: 14px; color: #111827; font-weight: 800;">
                                            {{ $clinic->name }}
                                        </td>
                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            {{ $clinic->city }}
                                        </td>
                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            {{ $clinic->address }}
                                        </td>
                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            {{ $clinic->doctors->count() }}
                                        </td>
                                        <td style="padding: 18px; font-size: 14px;">
                                            <a
                                                href="{{ route('admin.clinics.edit', $clinic) }}"
                                                style="display: inline-flex; align-items: center; justify-content: center; padding: 8px 14px; background: #dbeafe; color: #1d4ed8; border-radius: 9px; font-size: 12px; font-weight: 900; text-decoration: none;"
                                            >
                                                Edytuj
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div style="margin-top: 28px;">
                    {{ $clinics->links() }}
                </div>
            @else
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <p style="color: #6b7280;">
                        Brak klinik do wyświetlenia.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
