<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Specjalizacje
        </h2>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1100px; margin: 0 auto;">

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

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #2563eb; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                    Panel administratora
                </p>

                <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                    Słownik specjalizacji
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 850px;">
                    Administrator zarządza listą specjalizacji, które lekarze mogą wybierać w profilu
                    oraz które są używane w wyszukiwarce lekarzy.
                </p>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Filtry
                </h2>

                <p style="font-size: 14px; color: #6b7280; margin-bottom: 22px;">
                    Wyszukaj specjalizację po nazwie albo sprawdź, które specjalizacje są używane przez lekarzy.
                </p>

                <form method="GET" action="{{ route('admin.specializations') }}" style="display: flex; flex-direction: column; gap: 22px;">
                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label for="name" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Nazwa specjalizacji
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ request('name') }}"
                                placeholder="np. Dermatolog"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="usage" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Przypisanie do lekarzy
                            </label>

                            <select
                                id="usage"
                                name="usage"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                                <option value="">Wszystkie</option>
                                <option value="used" @selected(request('usage') === 'used')>
                                    Używane przez lekarzy
                                </option>
                                <option value="unused" @selected(request('usage') === 'unused')>
                                    Nieprzypisane do lekarzy
                                </option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 18px;">
                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 24px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer;"
                        >
                            Filtruj
                        </button>

                        <a
                            href="{{ route('admin.specializations') }}"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 24px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 12px; text-decoration: none;"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Dodaj specjalizację
                </h2>

                <p style="font-size: 14px; color: #6b7280; margin-bottom: 22px;">
                    Dodaj nową specjalizację do słownika. Nazwa nie może się powtarzać.
                </p>

                <form method="POST" action="{{ route('admin.specializations.store') }}" style="display: flex; align-items: center; gap: 16px;">
                    @csrf

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="np. Kardiolog"
                        required
                        style="flex: 1; border: 1px solid #d1d5db; border-radius: 12px; padding: 12px 14px; font-size: 14px;"
                    >

                    <button
                        type="submit"
                        style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer;"
                    >
                        Dodaj
                    </button>
                </form>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                @if ($specializations->count() > 0)
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 850px;">
                            <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Nazwa
                                    </th>

                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Przypisania do lekarzy
                                    </th>

                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Akcje
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($specializations as $specialization)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 18px;">
                                            <form method="POST" action="{{ route('admin.specializations.update', $specialization) }}" style="display: flex; align-items: center; gap: 14px;">
                                                @csrf
                                                @method('PUT')

                                                <input
                                                    type="text"
                                                    name="name"
                                                    value="{{ $specialization->name }}"
                                                    required
                                                    style="width: 100%; max-width: 360px; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                                >

                                                <button
                                                    type="submit"
                                                    style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 18px; background: #dbeafe; color: #1d4ed8; font-size: 13px; font-weight: 900; border-radius: 10px; border: none; cursor: pointer;"
                                                >
                                                    Zapisz
                                                </button>
                                            </form>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 42px; padding: 7px 12px; background: #f3f4f6; color: #374151; border-radius: 999px; font-size: 13px; font-weight: 900;">
                                                {{ $specialization->doctor_specializations_count }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px;">
                                            @if ($specialization->doctor_specializations_count == 0)
                                                <form method="POST" action="{{ route('admin.specializations.delete', $specialization) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Czy na pewno usunąć tę specjalizację?')"
                                                        style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 18px; background: #fee2e2; color: #b91c1c; font-size: 13px; font-weight: 900; border-radius: 10px; border: none; cursor: pointer;"
                                                    >
                                                        Usuń
                                                    </button>
                                                </form>
                                            @else
                                                <span style="font-size: 13px; color: #9ca3af; font-weight: 700;">
                                                    Nie można usunąć — specjalizacja jest używana.
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
                            Brak specjalizacji spełniających wybrane filtry.
                        </p>
                    </div>
                @endif
            </div>

            <div style="margin-top: 28px;">
                {{ $specializations->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
