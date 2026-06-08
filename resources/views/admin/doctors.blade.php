<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lekarze
        </h2>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1280px; margin: 0 auto;">

            @if (session('success'))
                <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 24px;">
                    <div>
                        <p style="color: #2563eb; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                            Panel administratora
                        </p>

                        <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                            Lista lekarzy
                        </h1>

                        <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 850px;">
                            Administrator może dodawać lekarzy, filtrować ich, edytować profile oraz zarządzać weryfikacją.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.doctors.create') }}"
                        style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; text-decoration: none; box-shadow: 0 8px 16px rgba(37, 99, 235, 0.18); white-space: nowrap;"
                    >
                        Dodaj lekarza
                    </a>
                </div>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Filtry
                </h2>

                <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
                    Wyszukaj lekarza po imieniu, nazwisku, emailu, specjalizacji albo statusie weryfikacji.
                </p>

                <form method="GET" action="{{ route('admin.doctors') }}" style="display: flex; flex-direction: column; gap: 22px;">
                    <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label for="name" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Imię / nazwisko
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ request('name') }}"
                                placeholder="np. Jan"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="email" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Email
                            </label>

                            <input
                                type="text"
                                id="email"
                                name="email"
                                value="{{ request('email') }}"
                                placeholder="np. doktor@test.pl"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="specialization" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Specjalizacja
                            </label>

                            <input
                                type="text"
                                id="specialization"
                                name="specialization"
                                value="{{ request('specialization') }}"
                                placeholder="np. Dermatolog"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="is_verified" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Status weryfikacji
                            </label>

                            <select
                                id="is_verified"
                                name="is_verified"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                                <option value="">Wszystkie</option>
                                <option value="1" @selected(request('is_verified') === '1')>
                                    Zweryfikowany
                                </option>
                                <option value="0" @selected(request('is_verified') === '0')>
                                    Niezweryfikowany
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
                            href="{{ route('admin.doctors') }}"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 24px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 12px; text-decoration: none;"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            @if ($doctors->count() > 0)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 1050px;">
                            <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">ID</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Lekarz</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Email</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Specjalizacje</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Status</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Akcje</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($doctors as $doctor)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 18px; font-size: 14px; color: #111827; font-weight: 800;">
                                            #{{ $doctor->id }}
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #111827;">
                                            <strong>Dr {{ $doctor->first_name }} {{ $doctor->last_name }}</strong>

                                            <br>

                                            @if ($doctor->is_for_children)
                                                <span style="display: inline-block; margin-top: 7px; padding: 5px 9px; background: #f0fdf4; color: #15803d; border-radius: 999px; font-size: 11px; font-weight: 900;">
                                                    Dorośli i dzieci
                                                </span>
                                            @else
                                                <span style="display: inline-block; margin-top: 7px; padding: 5px 9px; background: #f3f4f6; color: #6b7280; border-radius: 999px; font-size: 11px; font-weight: 900;">
                                                    Tylko dorośli
                                                </span>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            {{ $doctor->user->email ?? 'Brak emaila' }}
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            <div style="display: flex; flex-wrap: wrap; gap: 7px; max-width: 330px;">
                                                @forelse ($doctor->specializations as $specialization)
                                                    <span style="padding: 6px 10px; background: #eff6ff; color: #1d4ed8; border-radius: 999px; font-size: 11px; font-weight: 900;">
                                                        {{ $specialization->specialization_name }}
                                                    </span>
                                                @empty
                                                    <span style="padding: 6px 10px; background: #f3f4f6; color: #6b7280; border-radius: 999px; font-size: 11px; font-weight: 800;">
                                                        Brak specjalizacji
                                                    </span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            @if ($doctor->is_verified)
                                                <span style="padding: 6px 10px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Zweryfikowany
                                                </span>
                                            @else
                                                <span style="padding: 6px 10px; background: #fee2e2; color: #991b1b; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Niezweryfikowany
                                                </span>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            <div style="display: flex; flex-direction: column; gap: 8px; min-width: 155px;">
                                                <a
                                                    href="{{ route('admin.doctors.edit', $doctor) }}"
                                                    style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 8px 12px; background: #dbeafe; color: #1d4ed8; border-radius: 9px; font-size: 12px; font-weight: 900; text-decoration: none;"
                                                >
                                                    Edytuj
                                                </a>

                                                <form method="POST" action="{{ route('admin.doctors.toggle-verification', $doctor) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    @if ($doctor->is_verified)
                                                        <button
                                                            type="submit"
                                                            onclick="return confirm('Czy na pewno chcesz cofnąć weryfikację tego lekarza?')"
                                                            style="width: 100%; padding: 8px 12px; background: #fee2e2; color: #b91c1c; border-radius: 9px; border: none; font-size: 12px; font-weight: 900; cursor: pointer;"
                                                        >
                                                            Cofnij weryfikację
                                                        </button>
                                                    @else
                                                        <button
                                                            type="submit"
                                                            style="width: 100%; padding: 8px 12px; background: #dcfce7; color: #166534; border-radius: 9px; border: none; font-size: 12px; font-weight: 900; cursor: pointer;"
                                                        >
                                                            Zweryfikuj
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div style="margin-top: 28px;">
                    {{ $doctors->links() }}
                </div>
            @else
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <p style="color: #6b7280;">
                        Brak lekarzy spełniających wybrane filtry.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
