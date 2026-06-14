<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil lekarza
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

            @if ($message)
                <div style="background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    {{ $message }}
                </div>
            @endif

            @if ($doctor)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                    <div style="display: flex; align-items: center; gap: 28px;">
                        <div style="width: 120px; height: 120px; border-radius: 9999px; overflow: hidden; background: #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if ($doctor->photo_url)
                                <img
                                    src="{{ asset($doctor->photo_url) }}"
                                    alt="Zdjęcie lekarza"
                                    style="width: 120px; height: 120px; object-fit: cover; object-position: center 20%; display: block;"
                                >
                            @else
                                <span style="color: #6b7280; font-size: 32px; font-weight: 900;">
                                    {{ mb_substr($doctor->first_name, 0, 1) }}{{ mb_substr($doctor->last_name, 0, 1) }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <p style="color: #059669; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                                Panel lekarza
                            </p>

                            <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                                Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                            </h1>

                            <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 720px;">
                                Tutaj możesz edytować informacje widoczne dla pacjentów:
                                opis, specjalizacje, tagi pomocy, przyjmowanych pacjentów oraz zdjęcie profilowe.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('doctor.profile.update') }}" style="display: flex; flex-direction: column; gap: 28px;">
                    @csrf
                    @method('PATCH')

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Dane profilu lekarza
                        </h2>

                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 26px;">
                            Te informacje będą widoczne dla pacjentów na publicznym profilu lekarza.
                        </p>

                        <div>
                            <label for="bio" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 10px;">
                                Opis / bio
                            </label>

                            <textarea
                                id="bio"
                                name="bio"
                                rows="7"
                                placeholder="Napisz krótki opis doświadczenia, specjalizacji i sposobu pracy z pacjentami."
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 14px; font-size: 14px; resize: vertical;"
                            >{{ old('bio', $doctor->bio) }}</textarea>

                            <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                                Opis powinien krótko wyjaśniać pacjentowi, w czym możesz pomóc.
                            </p>
                        </div>

                        <div style="margin-top: 28px;">
                            <p style="font-size: 14px; font-weight: 800; color: #374151; margin-bottom: 12px;">
                                Przyjmowani pacjenci
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
                                        Przyjmuję dorosłych
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
                                        Przyjmuję dzieci
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Specjalizacje
                        </h2>

                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
                            Wybierz specjalizacje, które będą widoczne na Twoim profilu oraz w filtrach wyszukiwania.
                        </p>

                        @php
                            $doctorSpecializations = $doctor->specializations
                                ->pluck('specialization_id')
                                ->filter()
                                ->toArray();

                            $selectedSpecializations = old('specializations', $doctorSpecializations);
                        @endphp

                        @if ($availableSpecializations->count() > 0)
                            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                                @foreach ($availableSpecializations as $specialization)
                                    <label style="display: flex; align-items: center; gap: 12px; border: 1px solid #e5e7eb; border-radius: 14px; padding: 15px 16px; background: #f9fafb; cursor: pointer;">
                                        <input
                                            type="checkbox"
                                            name="specializations[]"
                                            value="{{ $specialization->id }}"
                                            @checked(in_array($specialization->id, $selectedSpecializations))
                                        >

                                        <span style="font-size: 14px; font-weight: 700; color: #374151;">
                                            {{ $specialization->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p style="font-size: 14px; color: #6b7280;">
                                Brak dostępnych specjalizacji. Administrator powinien dodać specjalizacje w systemie.
                            </p>
                        @endif
                    </div>

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Tagi / obszary pomocy
                        </h2>

                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
                            Tagi pomagają pacjentom znaleźć lekarza po problemie, np. ból głowy, gorączka, depresja.
                        </p>

                        @php
                            $doctorHelpTags = $doctor->helpTags
                                ->pluck('id')
                                ->toArray();

                            $selectedHelpTags = old('help_tags', $doctorHelpTags);
                        @endphp

                        @if ($helpTags->count() > 0)
                            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                                @foreach ($helpTags as $tag)
                                    <label style="display: flex; align-items: center; gap: 12px; border: 1px solid #e5e7eb; border-radius: 14px; padding: 15px 16px; background: #f9fafb; cursor: pointer;">
                                        <input
                                            type="checkbox"
                                            name="help_tags[]"
                                            value="{{ $tag->id }}"
                                            @checked(in_array($tag->id, $selectedHelpTags))
                                        >

                                        <span style="font-size: 14px; font-weight: 700; color: #374151;">
                                            {{ $tag->tag_name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 18px;">
                                Brak dostępnych tagów. Możesz dodać pierwszy tag poniżej.
                            </p>
                        @endif

                        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
                            <h3 style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 8px;">
                                Dodaj własny tag
                            </h3>

                            <p style="font-size: 13px; color: #6b7280; margin-bottom: 16px; line-height: 1.6;">
                                Jeśli brakuje tagu opisującego Twój obszar pomocy, możesz go dodać samodzielnie.
                                System sprawdzi podobieństwo nazwy z istniejącymi tagami.
                            </p>
                        </div>
                    </div>

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 28px 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 24px;">
                            <div>
                                <h2 style="font-size: 20px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                                    Zapisz zmiany profilu?
                                </h2>

                                <p style="font-size: 14px; color: #6b7280;">
                                    Zmiany będą widoczne dla pacjentów po zapisaniu.
                                </p>
                            </div>

                            <button
                                type="submit"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 13px 26px; background: #2563eb; color: white; font-size: 15px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 10px 20px rgba(37, 99, 235, 0.22);"
                            >
                                Zapisz profil
                            </button>
                        </div>
                    </div>
                </form>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 28px 32px; margin-top: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <form method="POST" action="{{ route('doctor.help-tags.store') }}" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 16px;">
                        @csrf

                        <div style="flex: 1; min-width: 260px;">
                            <label for="tag_name" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Nazwa nowego tagu
                            </label>

                            <input
                                type="text"
                                id="tag_name"
                                name="tag_name"
                                value="{{ old('tag_name') }}"
                                placeholder="np. ból pleców"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; background: #059669; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer;"
                        >
                            Dodaj tag
                        </button>
                    </form>
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-top: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Zmień zdjęcie profilowe
                    </h2>

                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
                        Zdjęcie będzie widoczne dla pacjentów na Twoim profilu oraz na liście lekarzy.
                    </p>

                    <form method="POST" action="{{ route('doctor.profile.photo') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 22px;">
                        @csrf

                        <div>
                            <label for="photo" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Zdjęcie lekarza
                            </label>

                            <input
                                type="file"
                                id="photo"
                                name="photo"
                                accept="image/jpeg,image/png,image/webp"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px; background: white;"
                            >

                            <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                                Dozwolone formaty: JPG, PNG, WEBP. Maksymalnie 2 MB.
                            </p>
                        </div>

                        <div>
                            <button
                                type="submit"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 26px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 8px 16px rgba(37, 99, 235, 0.18);"
                            >
                                Zapisz zdjęcie
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
