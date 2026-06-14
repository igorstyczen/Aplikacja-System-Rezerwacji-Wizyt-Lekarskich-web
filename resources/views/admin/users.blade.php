<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista użytkowników
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

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #2563eb; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                    Panel administratora
                </p>

                <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                    Użytkownicy systemu
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 900px;">
                    Administrator widzi listę kont, może filtrować użytkowników, edytować dane,
                    zmieniać role oraz aktywować lub dezaktywować konta.
                </p>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Filtry
                </h2>

                <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
                    Wyszukaj użytkownika po nazwie, adresie email, roli lub dacie utworzenia konta.
                </p>

                <form method="GET" action="{{ route('admin.users') }}" style="display: flex; flex-direction: column; gap: 22px;">
                    <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label for="name" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Imię / nazwa
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
                                placeholder="np. test.pl"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="role" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Rola
                            </label>

                            <select
                                id="role"
                                name="role"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                                <option value="">Wszystkie</option>
                                <option value="admin" @selected(request('role') === 'admin')>admin</option>
                                <option value="doctor" @selected(request('role') === 'doctor')>doctor</option>
                                <option value="patient" @selected(request('role') === 'patient')>patient</option>
                            </select>
                        </div>

                        <div>
                            <label for="created_from" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Utworzony od
                            </label>

                            <input
                                type="date"
                                id="created_from"
                                name="created_from"
                                value="{{ request('created_from') }}"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 10px 14px; font-size: 14px;"
                            >
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
                            href="{{ route('admin.users') }}"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 24px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 12px; text-decoration: none;"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            @if ($users->count() > 0)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 1180px;">
                            <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">ID</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Użytkownik</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Kontakt</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Rola</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Status</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase; min-width: 220px;">Nadaj rolę</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Data utworzenia</th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Akcje</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($users as $user)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 18px; font-size: 14px; color: #111827; font-weight: 800;">
                                            #{{ $user->id }}
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #111827;">
                                            <strong>{{ $user->name }}</strong>

                                            @if ($user->id === Auth::id())
                                                <br>
                                                <span style="display: inline-block; margin-top: 6px; padding: 5px 9px; background: #eff6ff; color: #1d4ed8; border-radius: 999px; font-size: 11px; font-weight: 900;">
                                                    To jest Twoje konto
                                                </span>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            {{ $user->email }}
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280;">
                                                {{ $user->phone ?? 'Brak telefonu' }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            @if ($user->role === 'admin')
                                                <span style="padding: 6px 10px; background: #f3e8ff; color: #7e22ce; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    admin
                                                </span>
                                            @elseif ($user->role === 'doctor')
                                                <span style="padding: 6px 10px; background: #dbeafe; color: #1d4ed8; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    doctor
                                                </span>
                                            @elseif ($user->role === 'patient')
                                                <span style="padding: 6px 10px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    patient
                                                </span>
                                            @else
                                                <span style="padding: 6px 10px; background: #f3f4f6; color: #374151; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    {{ $user->role }}
                                                </span>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            @if ($user->is_active)
                                                <span style="padding: 6px 10px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Aktywne
                                                </span>
                                            @else
                                                <span style="padding: 6px 10px; background: #fee2e2; color: #991b1b; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Nieaktywne
                                                </span>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; min-width: 280px;">
                                            @if ($user->id === Auth::id())
                                                <span style="font-size: 12px; color: #9ca3af; font-weight: 700;">
                                                    Nie można zmienić własnej roli
                                                </span>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.update-role', $user) }}" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                                    @csrf
                                                    @method('PATCH')

                                                    <select
                                                        name="role"
                                                        style="min-width: 200px; flex: 1; border: 1px solid #d1d5db; border-radius: 10px; padding: 10px 12px; font-size: 14px; font-weight: 700;"
                                                    >
                                                        <option value="patient" @selected($user->role === 'patient')>
                                                            Pacjent
                                                        </option>
                                                        <option value="doctor" @selected($user->role === 'doctor')>
                                                            Lekarz
                                                        </option>
                                                        <option value="admin" @selected($user->role === 'admin')>
                                                            Administrator
                                                        </option>
                                                    </select>

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Czy na pewno chcesz zmienić rolę tego użytkownika?')"
                                                        style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 16px; background: #dbeafe; color: #1d4ed8; border-radius: 9px; border: none; font-size: 13px; font-weight: 900; cursor: pointer; white-space: nowrap;"
                                                    >
                                                        Zapisz rolę
                                                    </button>
                                                </form>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151; white-space: nowrap;">
                                            {{ $user->created_at->format('d.m.Y') }}
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280;">
                                                {{ $user->created_at->format('H:i') }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            <div style="display: flex; flex-direction: column; gap: 8px; min-width: 135px;">
                                                <a
                                                    href="{{ route('admin.users.edit', $user) }}"
                                                    style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 8px 12px; background: #dbeafe; color: #1d4ed8; border-radius: 9px; font-size: 12px; font-weight: 900; text-decoration: none;"
                                                >
                                                    Edytuj
                                                </a>

                                                @if ($user->id !== Auth::id())
                                                    <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        @if ($user->is_active)
                                                            <button
                                                                type="submit"
                                                                onclick="return confirm('Czy na pewno chcesz dezaktywować tego użytkownika?')"
                                                                style="width: 100%; padding: 8px 12px; background: #fee2e2; color: #b91c1c; border-radius: 9px; border: none; font-size: 12px; font-weight: 900; cursor: pointer;"
                                                            >
                                                                Dezaktywuj
                                                            </button>
                                                        @else
                                                            <button
                                                                type="submit"
                                                                style="width: 100%; padding: 8px 12px; background: #dcfce7; color: #166534; border-radius: 9px; border: none; font-size: 12px; font-weight: 900; cursor: pointer;"
                                                            >
                                                                Aktywuj
                                                            </button>
                                                        @endif
                                                    </form>
                                                @else
                                                    <span style="font-size: 12px; color: #9ca3af; font-weight: 700; text-align: center;">
                                                        Brak dezaktywacji
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
                    {{ $users->links() }}
                </div>
            @else
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <p style="color: #6b7280;">
                        Brak użytkowników do wyświetlenia.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
