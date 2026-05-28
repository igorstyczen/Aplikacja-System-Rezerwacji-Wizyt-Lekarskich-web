<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lekarze
        </h2>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1280px; margin: 0 auto;">

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 28px; padding: 42px; margin-bottom: 30px; box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);">
                <div style="display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.6fr); gap: 32px; align-items: center;">
                    <div>
                        <p style="color: #059669; font-size: 14px; font-weight: 900; margin-bottom: 10px;">
                            Platforma rezerwacji wizyt lekarskich
                        </p>

                        <h1 style="font-size: 40px; line-height: 1.15; font-weight: 900; color: #111827; margin-bottom: 16px;">
                            Znajdź lekarza i umów wizytę online
                        </h1>

                        <p style="color: #4b5563; font-size: 16px; line-height: 1.8; max-width: 820px;">
                            Wybierz lekarza, sprawdź specjalizacje, kliniki, dostępne terminy i zarezerwuj wizytę.
                            Możesz filtrować lekarzy po mieście, specjalizacji oraz problemie zdrowotnym.
                        </p>

                        <div style="display: flex; flex-wrap: wrap; gap: 14px; margin-top: 26px;">
                            <a
                                href="#search-doctors"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; text-decoration: none; box-shadow: 0 10px 20px rgba(37, 99, 235, 0.18);"
                            >
                                Znajdź lekarza
                            </a>

                            <a
                                href="{{ route('nfz.comparison') }}"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; background: #ecfdf5; color: #047857; font-size: 14px; font-weight: 900; border-radius: 12px; text-decoration: none;"
                            >
                                Porównaj z NFZ
                            </a>
                        </div>
                    </div>

                    <div style="background: #ecfdf5; border: 1px solid #bbf7d0; border-radius: 24px; padding: 28px;">
                        <h2 style="font-size: 20px; font-weight: 900; color: #064e3b; margin-bottom: 16px;">
                            Co możesz zrobić?
                        </h2>

                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            <div style="background: white; border-radius: 16px; padding: 16px;">
                                <p style="font-size: 14px; font-weight: 900; color: #111827;">
                                    1. Wyszukaj lekarza
                                </p>
                                <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                                    Po specjalizacji, mieście albo tagu.
                                </p>
                            </div>

                            <div style="background: white; border-radius: 16px; padding: 16px;">
                                <p style="font-size: 14px; font-weight: 900; color: #111827;">
                                    2. Wybierz termin
                                </p>
                                <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                                    Sprawdź dostępny grafik lekarza.
                                </p>
                            </div>

                            <div style="background: white; border-radius: 16px; padding: 16px;">
                                <p style="font-size: 14px; font-weight: 900; color: #111827;">
                                    3. Zarezerwuj wizytę
                                </p>
                                <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                                    Potwierdź i opłać wizytę testowo.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="search-doctors" style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-bottom: 30px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h2 style="font-size: 24px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Znajdź lekarza
                </h2>

                <p style="color: #6b7280; font-size: 14px; margin-bottom: 26px;">
                    Skorzystaj z filtrów, aby znaleźć lekarza według nazwiska, specjalizacji, problemu lub miasta.
                </p>

                <form method="GET" action="{{ route('home') }}" style="display: flex; flex-direction: column; gap: 24px;">
                    <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label for="search" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Szukaj lekarza
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Imię lub nazwisko"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="specialization" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Specjalizacja
                            </label>

                            <select
                                id="specialization"
                                name="specialization"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                                <option value="">Wszystkie</option>

                                @foreach ($specializations as $specialization)
                                    <option value="{{ $specialization }}" @selected(request('specialization') === $specialization)>
                                        {{ $specialization }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tag" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Problem / tag
                            </label>

                            <select
                                id="tag"
                                name="tag"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                                <option value="">Wszystkie</option>

                                @foreach ($tags as $tag)
                                    <option value="{{ $tag }}" @selected(request('tag') === $tag)>
                                        {{ $tag }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="city" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Miasto
                            </label>

                            <select
                                id="city"
                                name="city"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                                <option value="">Wszystkie</option>

                                @foreach ($cities as $city)
                                    <option value="{{ $city }}" @selected(request('city') === $city)>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 18px;">
                        <label style="display: inline-flex; align-items: center; gap: 10px; padding: 13px 16px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; cursor: pointer;">
                            <input
                                type="checkbox"
                                name="for_children"
                                value="1"
                                @checked(request()->boolean('for_children'))
                            >

                            <span style="font-size: 14px; font-weight: 700; color: #374151;">
                                Przyjmuje dzieci
                            </span>
                        </label>

                        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 18px;">
                            <button
                                type="submit"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 24px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer;"
                            >
                                Filtruj
                            </button>

                            <a
                                href="{{ route('home') }}"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 24px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 12px; text-decoration: none;"
                            >
                                Wyczyść
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if ($doctors->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px;">
                    @foreach ($doctors as $doctor)
                        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 26px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05); display: flex; flex-direction: column; justify-content: space-between; min-height: 390px; min-width: 0; overflow: hidden;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 18px; margin-bottom: 18px;">
                                    <div style="width: 78px; height: 78px; border-radius: 9999px; overflow: hidden; background: #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        @if ($doctor->photo_url)
                                            <img
                                                src="{{ asset($doctor->photo_url) }}"
                                                alt="Zdjęcie lekarza"
                                                style="width: 78px; height: 78px; object-fit: cover; object-position: center 20%; display: block;"
                                            >
                                        @else
                                            <span style="color: #6b7280; font-size: 20px; font-weight: 900;">
                                                {{ mb_substr($doctor->first_name, 0, 1) }}{{ mb_substr($doctor->last_name, 0, 1) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div>
                                        <h3 style="font-size: 19px; font-weight: 900; color: #111827; margin-bottom: 5px;">
                                            Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </h3>

                                        <p style="font-size: 13px; color: #6b7280; line-height: 1.5;">
                                            @forelse ($doctor->specializations as $specialization)
                                                {{ $specialization->specialization_name }}@if (!$loop->last), @endif
                                            @empty
                                                Brak specjalizacji
                                            @endforelse
                                        </p>
                                    </div>
                                </div>

                                <p style="font-size: 14px; color: #4b5563; line-height: 1.7; margin-bottom: 18px; overflow-wrap: anywhere; word-break: break-word;">
                                    {{ \Illuminate\Support\Str::limit($doctor->bio ?? 'Brak opisu lekarza.', 125) }}
                                </p>

                                <div style="margin-bottom: 18px;">
                                    <p style="font-size: 13px; font-weight: 900; color: #374151; margin-bottom: 8px;">
                                        Kliniki
                                    </p>

                                    @forelse ($doctor->clinics as $clinic)
                                        <p style="font-size: 13px; color: #6b7280; margin-bottom: 4px;">
                                            {{ $clinic->name }}, {{ $clinic->city }}
                                        </p>
                                    @empty
                                        <p style="font-size: 13px; color: #9ca3af;">
                                            Brak przypisanej kliniki
                                        </p>
                                    @endforelse
                                </div>

                                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px;">
                                    @forelse ($doctor->helpTags as $tag)
                                        <span style="padding: 6px 10px; background: #eff6ff; color: #1d4ed8; border-radius: 999px; font-size: 11px; font-weight: 900;">
                                            {{ $tag->tag_name }}
                                        </span>
                                    @empty
                                        <span style="padding: 6px 10px; background: #f3f4f6; color: #6b7280; border-radius: 999px; font-size: 11px; font-weight: 800;">
                                            Brak tagów
                                        </span>
                                    @endforelse
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 14px; border-top: 1px solid #f3f4f6; padding-top: 18px;">
                                <div style="font-size: 13px; color: #6b7280; font-weight: 800;">
                                    @if ($doctor->is_for_children)
                                        Dorośli i dzieci
                                    @else
                                        Tylko dorośli
                                    @endif
                                </div>

                                <a
                                    href="{{ route('doctors.show', $doctor) }}"
                                    style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 16px; background: #2563eb; color: white; font-size: 13px; font-weight: 900; border-radius: 10px; text-decoration: none;"
                                >
                                    Zobacz profil
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 30px;">
                    {{ $doctors->links() }}
                </div>
            @else
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <p style="color: #6b7280;">
                        Brak lekarzy do wyświetlenia.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
