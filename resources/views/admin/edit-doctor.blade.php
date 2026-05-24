<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edycja lekarza
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

            @php
                $selectedSpecializationIds = old(
                    'specializations',
                    $doctor->specializations->pluck('specialization_id')->filter()->values()->all()
                );

                $selectedHelpTagIds = old(
                    'help_tags',
                    $doctor->helpTags->pluck('id')->values()->all()
                );
            @endphp

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #2563eb; font-size: 14px; font-weight: 800; margin-bottom: 8px;">
                    Panel administratora
                </p>

                <h1 style="font-size: 30px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                    {{ $doctor->first_name }} {{ $doctor->last_name }}
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7;">
                    Email konta: {{ $doctor->user->email ?? 'Brak emaila' }}
                </p>
            </div>

            <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 28px;">
                @csrf
                @method('PUT')

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Dane profilu lekarza
                    </h2>

                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 26px;">
                        Te informacje są widoczne dla pacjentów.
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Imię
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                value="{{ old('first_name', $doctor->first_name) }}"
                                required
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
                                value="{{ old('last_name', $doctor->last_name) }}"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>
                    </div>

                    <div style="margin-top: 28px;">
                        <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 10px;">
                            Aktualne zdjęcie lekarza
                        </label>

                        <div style="width: 112px; height: 112px; border-radius: 9999px; overflow: hidden; background: #e5e7eb; display: flex; align-items: center; justify-content: center;">
                            @if ($doctor->photo_url)
                                <img
                                    src="{{ asset($doctor->photo_url) }}"
                                    alt="Zdjęcie lekarza"
                                    style="width: 112px; height: 112px; object-fit: cover; object-position: center 20%; display: block;"
                                >
                            @else
                                <span style="color: #6b7280; font-size: 28px; font-weight: 900;">
                                    {{ mb_substr($doctor->first_name, 0, 1) }}{{ mb_substr($doctor->last_name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div style="margin-top: 28px;">
                        <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 10px;">
                            Zmień zdjęcie lekarza
                        </label>

                        <input
                            type="file"
                            name="photo"
                            accept="image/jpeg,image/png,image/webp"
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px; background: white;"
                        >

                        <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                            Dozwolone formaty: JPG, PNG, WEBP. Maksymalnie 2 MB. Jeśli nie wybierzesz pliku, obecne zdjęcie zostanie bez zmian.
                        </p>
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
                        >{{ old('bio', $doctor->bio) }}</textarea>
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
                                    @checked(old('is_for_adults', $doctor->is_for_adults))
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
                                    @checked(old('is_for_children', $doctor->is_for_children))
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
                                    @checked(old('is_verified', $doctor->is_verified))
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
                        Zmień specjalizacje lekarza.
                    </p>

                    @if ($specializations->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px;">
                            @foreach ($specializations as $specialization)
                                <label style="display: flex; align-items: center; gap: 12px; border: 1px solid #e5e7eb; border-radius: 14px; padding: 15px 16px; background: #f9fafb; cursor: pointer;">
                                    <input
                                        type="checkbox"
                                        name="specializations[]"
                                        value="{{ $specialization->id }}"
                                        @checked(in_array($specialization->id, $selectedSpecializationIds))
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
                        Zmień problemy lub obszary pomocy, po których pacjent znajdzie lekarza.
                    </p>

                    @if ($helpTags->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px;">
                            @foreach ($helpTags as $tag)
                                <label style="display: flex; align-items: center; gap: 12px; border: 1px solid #e5e7eb; border-radius: 14px; padding: 15px 16px; background: #f9fafb; cursor: pointer;">
                                    <input
                                        type="checkbox"
                                        name="help_tags[]"
                                        value="{{ $tag->id }}"
                                        @checked(in_array($tag->id, $selectedHelpTagIds))
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
                                Zapisz zmiany?
                            </h2>

                            <p style="font-size: 14px; color: #6b7280;">
                                Zmiany będą widoczne dla pacjentów po zapisaniu.
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
                                Zapisz zmiany
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
