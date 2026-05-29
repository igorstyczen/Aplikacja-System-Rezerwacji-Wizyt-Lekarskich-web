<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Załóż profil lekarza
        </h2>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1050px; margin: 0 auto;">

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

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #059669; font-size: 14px; font-weight: 800; margin-bottom: 8px;">
                    Zgłoszenie lekarza
                </p>

                <h1 style="font-size: 32px; line-height: 1.2; font-weight: 900; color: #111827; margin-bottom: 12px;">
                    Utwórz profil lekarza
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 760px;">
                    Wypełnij dane zawodowe, wybierz specjalizacje, tagi pomocy i podaj miejsce przyjmowania pacjentów.
                    Administrator sprawdzi zgłoszenie i po akceptacji otrzymasz dostęp do panelu lekarza.
                </p>
            </div>

            @if ($existingApplication)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 18px; padding: 26px; margin-bottom: 28px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);">
                    <h2 style="font-size: 20px; font-weight: 800; color: #111827; margin-bottom: 10px;">
                        Twoje ostatnie zgłoszenie
                    </h2>

                    <p style="font-size: 14px; color: #374151;">
                        Status:
                        @if ($existingApplication->status === 'pending')
                            <span style="font-weight: 800; color: #a16207;">Oczekuje na decyzję administratora</span>
                        @elseif ($existingApplication->status === 'approved')
                            <span style="font-weight: 800; color: #15803d;">Zaakceptowane</span>
                        @else
                            <span style="font-weight: 800; color: #b91c1c;">Odrzucone</span>
                        @endif
                    </p>

                    @if ($existingApplication->admin_note)
                        <p style="font-size: 14px; color: #4b5563; margin-top: 10px;">
                            Wiadomość od administratora: {{ $existingApplication->admin_note }}
                        </p>
                    @endif
                </div>
            @endif

            @if (! $existingApplication || $existingApplication->status === 'rejected')
                <form method="POST" action="{{ route('doctor-applications.store') }}" style="display: flex; flex-direction: column; gap: 28px;">
                    @csrf

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Dane lekarza
                        </h2>

                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 26px;">
                            Podaj podstawowe dane, które będą widoczne dla pacjentów po akceptacji profilu.
                        </p>

                        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px;">
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                    Imię
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
                                    Nazwisko
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

                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                    Telefon
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    maxlength="25"
                                    pattern="^(\+|00)?[0-9\s\-]{9,20}$"
                                    placeholder="np. 500 600 700 albo +48 500 600 700"
                                    title="Podaj poprawny numer telefonu, np. 500 600 700, +48 500 600 700 albo +380 123 456 789."
                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                >
                            </div>
                        </div>

                        <div style="margin-top: 30px;">
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 10px;">
                                Opis doświadczenia / bio
                            </label>

                            <textarea
                                name="bio"
                                rows="8"
                                required
                                placeholder="Napisz o swoim doświadczeniu, specjalizacji, podejściu do pacjentów i zakresie usług."
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 14px; font-size: 14px; resize: vertical;"
                            >{{ old('bio') }}</textarea>

                            <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                                Minimum 20 znaków. Ten opis będzie widoczny dla pacjentów po zaakceptowaniu profilu.
                            </p>
                        </div>

                        <div style="margin-top: 30px;">
                            <p style="font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 12px;">
                                Przyjmowani pacjenci
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
                                        Przyjmuję dorosłych
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
                                        Przyjmuję dzieci
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
                            Wybierz jedną lub kilka specjalizacji z listy utworzonej przez administratora.
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
                                Brak specjalizacji w systemie. Administrator musi najpierw dodać specjalizacje.
                            </p>
                        @endif
                    </div>

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Tagi / obszary pomocy
                        </h2>

                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
                            Wybierz problemy lub obszary, w których pomagasz pacjentom. Tagi ułatwiają pacjentom wyszukanie lekarza.
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
                            <p style="font-size: 14px; color: #9ca3af;">
                                Brak tagów w systemie. Administrator może dodać je w panelu admina.
                            </p>
                        @endif
                    </div>

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Miejsce przyjmowania
                        </h2>

                        <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-bottom: 24px;">
                            Podaj pełny adres miejsca, w którym przyjmujesz pacjentów.
                            Jeśli taka klinika istnieje już w bazie, system przypisze Cię do niej.
                            Jeśli nie istnieje, administrator utworzy ją podczas akceptacji.
                        </p>

                        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px;">
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                    Nazwa placówki
                                </label>

                                <input
                                    type="text"
                                    name="clinic_name"
                                    value="{{ old('clinic_name') }}"
                                    required
                                    placeholder="np. Przychodnia Zdrowie"
                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                >
                            </div>

                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                    Miasto
                                </label>

                                <input
                                    type="text"
                                    name="clinic_city"
                                    value="{{ old('clinic_city') }}"
                                    required
                                    placeholder="np. Rzeszów"
                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                >
                            </div>

                            <div style="grid-column: 1 / -1;">
                                <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                    Pełny adres
                                </label>

                                <input
                                    type="text"
                                    name="clinic_address"
                                    value="{{ old('clinic_address') }}"
                                    required
                                    placeholder="np. ul. Medyczna 10"
                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                >
                            </div>

                            <div style="grid-column: 1 / -1;">
                                <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                    Szczegóły miejsca
                                </label>

                                <textarea
                                    name="clinic_details"
                                    rows="4"
                                    placeholder="np. gabinet 12, pierwsze piętro, wejście od parkingu"
                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 14px; font-size: 14px; resize: vertical;"
                                >{{ old('clinic_details') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 28px 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 24px;">
                            <div>
                                <h2 style="font-size: 20px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                                    Gotowe do wysłania?
                                </h2>

                                <p style="font-size: 14px; color: #6b7280;">
                                    Po wysłaniu zgłoszenie trafi do administratora.
                                </p>
                            </div>

                            <button
                                type="submit"
                                style="min-width: 210px; display: inline-flex; align-items: center; justify-content: center; padding: 14px 28px; background: #059669; color: white; font-size: 15px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 10px 20px rgba(5, 150, 105, 0.22);"
                            >
                                Wyślij zgłoszenie
                            </button>
                        </div>
                    </div>
                </form>
            @endif

        </div>
    </div>
</x-app-layout>
