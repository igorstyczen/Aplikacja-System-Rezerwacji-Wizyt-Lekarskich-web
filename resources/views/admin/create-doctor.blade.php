<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dodaj lekarza
            </h2>

            <a href="{{ route('admin.doctors') }}" style="font-size: 14px; color: #2563eb; text-decoration: none;">
                ← Wróć do listy lekarzy
            </a>
        </div>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1050px; margin: 0 auto;">

            @if ($errors->any())
                <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 0 0 6px 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #2563eb; font-size: 14px; font-weight: 800; margin-bottom: 8px;">
                    Panel administratora
                </p>

                <h1 style="font-size: 30px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                    Nowy lekarz
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7;">
                    Formularz tworzy konto użytkownika z rolą lekarza oraz kompletny profil lekarza ze specjalizacjami i tagami pomocy.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 28px;">
                @csrf

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Dane konta użytkownika
                    </h2>

                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 26px;">
                        Te dane służą do logowania lekarza do systemu.
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Nazwa użytkownika
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="np. Jan Kowalski"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="np. doktor@test.pl"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Numer telefonu
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                maxlength="25"
                                pattern="^(\+|00)?[0-9\s\-]{9,20}$"
                                placeholder="np. 500 600 700 albo +48 500 600 700"
                                title="Podaj poprawny numer telefonu, np. 500 600 700, +48 500 600 700 albo +380 123 456 789."
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >

                            <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                                Dozwolone formaty: 500 600 700, +48 500 600 700, +380 123 456 789 lub 0048 500 600 700.
                            </p>
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Hasło
                            </label>

                            <input
                                type="password"
                                name="password"
                                required
                                placeholder="minimum 8 znaków"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>
                    </div>
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Dane profilu lekarza
                    </h2>

                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 26px;">
                        Te informacje będą widoczne dla pacjentów.
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Imię lekarza
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                required
                                placeholder="np. Jan"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Nazwisko lekarza
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                required
                                placeholder="np. Kowalski"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>
                    </div>

                    <div style="margin-top: 28px;">
                        <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 10px;">
                            Opis lekarza
                        </label>

                        <textarea
                            name="bio"
                            rows="7"
                            placeholder="Opis lekarza widoczny na profilu..."
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 14px; font-size: 14px; resize: vertical;"
                        >{{ old('bio') }}</textarea>
                    </div>

                    <div style="margin-top: 28px;">
                        <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 10px;">
                            Zdjęcie lekarza
                        </label>

                        <input
                            type="file"
                            name="photo"
                            accept="image/jpeg,image/png,image/webp"
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px; background: white;"
                        >

                        <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                            Dozwolone formaty: JPG, PNG, WEBP. Maksymalnie 2 MB.
                        </p>
                    </div>

                    <div style="margin-top: 28px;">
                        <p style="font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 12px;">
                            Ustawienia profilu
                        </p>

                        <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                            <label style="display: inline-flex; align-items: center; gap: 10px; padding: 13px 16px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; cursor: pointer;">
                                <input
                                    type="checkbox"
                                    name="is_for_adults"
                                    value="1"
                                    @checked(old('is_for_adults', true))
                                >

                                <span style="font-size: 14px; font-weight: 700; color: #374151;">
                                    Przyjmuje dorosłych
                                </span>
                            </label>

                            <label style="display: inline-flex; align-items: center; gap: 10px; padding: 13px 16px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; cursor: pointer;">
                                <input
                                    type="checkbox"
                                    name="is_for_children"
                                    value="1"
                                    @checked(old('is_for_children'))
                                >

                                <span style="font-size: 14px; font-weight: 700; color: #374151;">
                                    Przyjmuje dzieci
                                </span>
                            </label>

                            <label style="display: inline-flex; align-items: center; gap: 10px; padding: 13px 16px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; cursor: pointer;">
                                <input
                                    type="checkbox"
                                    name="is_verified"
                                    value="1"
                                    @checked(old('is_verified', true))
                                >

                                <span style="font-size: 14px; font-weight: 700; color: #374151;">
                                    Lekarz zweryfikowany
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Specjalizacje
                    </h2>

                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
                        Wybierz jedną lub kilka specjalizacji lekarza.
                    </p>

                    @if ($specializations->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px;">
                            @foreach ($specializations as $specialization)
                                <label style="display: flex; align-items: center; gap: 12px; border: 1px solid #e5e7eb; border-radius: 14px; padding: 15px 16px; background: #f9fafb; cursor: pointer;">
                                    <input
                                        type="checkbox"
                                        name="specializations[]"
                                        value="{{ $specialization->id }}"
                                        @checked(in_array($specialization->id, old('specializations', [])))
                                    >

                                    <span style="font-size: 14px; font-weight: 700; color: #374151;">
                                        {{ $specialization->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p style="font-size: 14px; color: #b91c1c;">
                            Brak specjalizacji w systemie. Najpierw dodaj specjalizacje w słowniku admina.
                        </p>
                    @endif
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Tagi / obszary pomocy
                    </h2>

                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
                        Wybierz problemy lub obszary pomocy, po których pacjent będzie mógł znaleźć lekarza.
                    </p>

                    @if ($helpTags->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px;">
                            @foreach ($helpTags as $tag)
                                <label style="display: flex; align-items: center; gap: 12px; border: 1px solid #e5e7eb; border-radius: 14px; padding: 15px 16px; background: #f9fafb; cursor: pointer;">
                                    <input
                                        type="checkbox"
                                        name="help_tags[]"
                                        value="{{ $tag->id }}"
                                        @checked(in_array($tag->id, old('help_tags', [])))
                                    >

                                    <span style="font-size: 14px; font-weight: 700; color: #374151;">
                                        {{ $tag->tag_name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p style="font-size: 14px; color: #b91c1c;">
                            Brak tagów w systemie. Najpierw dodaj tagi w słowniku admina.
                        </p>
                    @endif
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 28px 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 24px;">
                        <div>
                            <h2 style="font-size: 20px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                                Gotowe?
                            </h2>

                            <p style="font-size: 14px; color: #6b7280;">
                                Po zapisaniu lekarz będzie widoczny zgodnie z ustawieniami weryfikacji.
                            </p>
                        </div>

                        <div style="display: flex; align-items: center; gap: 16px;">
                            <a
                                href="{{ route('admin.doctors') }}"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 13px 22px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 12px; text-decoration: none;"
                            >
                                Anuluj
                            </a>

                            <button
                                type="submit"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 13px 26px; background: #2563eb; color: white; font-size: 15px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 10px 20px rgba(37, 99, 235, 0.22);"
                            >
                                Dodaj lekarza
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
