<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lekarze
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 bg-white p-6 rounded-lg shadow-sm">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Platforma rezerwacji wizyt lekarskich
                </h1>

                <p class="text-gray-600">
                    Wybierz lekarza, sprawdź dostępne terminy i umów wizytę.
                </p>
            </div>

            <div class="mb-6 bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-2">
                    Porównaj termin prywatny z NFZ
                </h2>

                <p class="text-gray-600 mb-4">
                    Wpisz świadczenie i miasto. System porówna najbliższy prywatny termin w aplikacji z najbliższym terminem zwróconym przez API NFZ.
                </p>

                <form method="GET" action="{{ route('home') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="nfz_benefit" class="block text-sm font-medium text-gray-700 mb-1">
                                Świadczenie
                            </label>

                            <input
                                type="text"
                                id="nfz_benefit"
                                name="nfz_benefit"
                                value="{{ request('nfz_benefit') }}"
                                placeholder="np. kardiolog, dermatolog"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="nfz_locality" class="block text-sm font-medium text-gray-700 mb-1">
                                Miasto
                            </label>

                            <input
                                type="text"
                                id="nfz_locality"
                                name="nfz_locality"
                                value="{{ request('nfz_locality', request('city')) }}"
                                placeholder="np. Rzeszów"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="nfz_province" class="block text-sm font-medium text-gray-700 mb-1">
                                Województwo NFZ
                            </label>

                            <select
                                id="nfz_province"
                                name="nfz_province"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                                <option value="09" @selected(request('nfz_province', '09') === '09')>Podkarpackie</option>
                                <option value="06" @selected(request('nfz_province') === '06')>Małopolskie</option>
                                <option value="07" @selected(request('nfz_province') === '07')>Mazowieckie</option>
                                <option value="12" @selected(request('nfz_province') === '12')>Śląskie</option>
                                <option value="03" @selected(request('nfz_province') === '03')>Lubelskie</option>
                                <option value="02" @selected(request('nfz_province') === '02')>Kujawsko-pomorskie</option>
                                <option value="04" @selected(request('nfz_province') === '04')>Lubuskie</option>
                                <option value="05" @selected(request('nfz_province') === '05')>Łódzkie</option>
                                <option value="08" @selected(request('nfz_province') === '08')>Opolskie</option>
                                <option value="10" @selected(request('nfz_province') === '10')>Podlaskie</option>
                                <option value="11" @selected(request('nfz_province') === '11')>Pomorskie</option>
                                <option value="13" @selected(request('nfz_province') === '13')>Świętokrzyskie</option>
                                <option value="14" @selected(request('nfz_province') === '14')>Warmińsko-mazurskie</option>
                                <option value="15" @selected(request('nfz_province') === '15')>Wielkopolskie</option>
                                <option value="16" @selected(request('nfz_province') === '16')>Zachodniopomorskie</option>
                                <option value="01" @selected(request('nfz_province') === '01')>Dolnośląskie</option>
                            </select>
                        </div>

                        <div>
                            <label for="nfz_case" class="block text-sm font-medium text-gray-700 mb-1">
                                Typ przypadku
                            </label>

                            <select
                                id="nfz_case"
                                name="nfz_case"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                                <option value="1" @selected(request('nfz_case', '1') == 1)>Stabilny</option>
                                <option value="2" @selected(request('nfz_case') == 2)>Pilny</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center pt-4" style="gap: 18px;">
                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; padding: 10px 20px; background: #16a34a; color: white; font-size: 14px; font-weight: 700; border-radius: 8px; border: none; cursor: pointer;"
                        >
                            Porównaj terminy
                        </button>

                        <a
                            href="{{ route('home') }}"
                            style="display: inline-flex; align-items: center; padding: 10px 20px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 600; border-radius: 8px; text-decoration: none;"
                        >
                            Wyczyść porównanie
                        </a>
                    </div>
                </form>

                @if ($nfzSearched)
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">
                                Najbliższy termin prywatny
                            </h3>

                            @if ($privateSlot)
                                <p class="text-sm text-gray-700">
                                    <strong>Data:</strong>
                                    {{ \Carbon\Carbon::parse($privateSlot->start_time)->format('d.m.Y H:i') }}
                                </p>

                                <p class="text-sm text-gray-700">
                                    <strong>Lekarz:</strong>
                                    Dr {{ $privateSlot->doctor_first_name }} {{ $privateSlot->doctor_last_name }}
                                </p>

                                <p class="text-sm text-gray-700">
                                    <strong>Usługa:</strong>
                                    {{ $privateSlot->service_name }}
                                </p>

                                <p class="text-sm text-gray-700">
                                    <strong>Klinika:</strong>
                                    {{ $privateSlot->clinic_name }}, {{ $privateSlot->clinic_city }}
                                </p>

                                <p class="text-sm text-gray-700">
                                    <strong>Cena:</strong>
                                    {{ number_format($privateSlot->service_price, 2) }} zł
                                </p>

                                <a
                                    href="{{ route('doctors.show', $privateSlot->doctor_id) }}"
                                    class="inline-block mt-3 px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"
                                >
                                    Przejdź do lekarza
                                </a>
                            @else
                                <p class="text-sm text-gray-500">
                                    Brak pasującego prywatnego terminu w systemie.
                                </p>
                            @endif
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">
                                Najbliższy termin NFZ
                            </h3>

                            @if ($nfzResult && ! $nfzResult['success'])
                                <p class="text-sm text-red-600">
                                    {{ $nfzResult['message'] }}
                                </p>
                            @elseif ($nfzResult && $nfzResult['nearest'])
                                <p class="text-sm text-gray-700">
                                    <strong>Data:</strong>
                                    {{ \Carbon\Carbon::parse($nfzResult['nearest']['date'])->format('d.m.Y') }}
                                </p>

                                <p class="text-sm text-gray-700">
                                    <strong>Świadczenie:</strong>
                                    {{ $nfzResult['nearest']['benefit'] }}
                                </p>

                                <p class="text-sm text-gray-700">
                                    <strong>Placówka:</strong>
                                    {{ $nfzResult['nearest']['provider'] }}
                                </p>

                                <p class="text-sm text-gray-700">
                                    <strong>Miejsce:</strong>
                                    {{ $nfzResult['nearest']['place'] }}
                                </p>

                                <p class="text-sm text-gray-700">
                                    <strong>Adres:</strong>
                                    {{ $nfzResult['nearest']['address'] }}, {{ $nfzResult['nearest']['locality'] }}
                                </p>

                                @if ($nfzResult['nearest']['phone'])
                                    <p class="text-sm text-gray-700">
                                        <strong>Telefon:</strong>
                                        {{ $nfzResult['nearest']['phone'] }}
                                    </p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">
                                    Brak znalezionych terminów NFZ dla podanych danych.
                                </p>
                            @endif
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">
                                Wynik porównania
                            </h3>

                            @if (! is_null($differenceDays))
                                @if ($differenceDays > 0)
                                    <p class="text-green-700 font-semibold">
                                        Prywatnie szybciej o {{ $differenceDays }} dni.
                                    </p>
                                @elseif ($differenceDays < 0)
                                    <p class="text-blue-700 font-semibold">
                                        NFZ szybciej o {{ abs($differenceDays) }} dni.
                                    </p>
                                @else
                                    <p class="text-gray-700 font-semibold">
                                        Terminy są tego samego dnia.
                                    </p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">
                                    Nie można policzyć różnicy, ponieważ brakuje terminu prywatnego albo NFZ.
                                </p>
                            @endif

                            @if ($nfzResult && ! empty($nfzResult['items']))
                                <p class="text-xs text-gray-500 mt-3">
                                    API NFZ zwróciło {{ count($nfzResult['items']) }} wyników. Pokazano najbliższy termin.
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="mb-6 bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    Znajdź lekarza
                </h2>

                <form method="GET" action="{{ route('home') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                Szukaj lekarza
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Imię lub nazwisko"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">
                                Specjalizacja
                            </label>

                            <select
                                id="specialization"
                                name="specialization"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
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
                            <label for="tag" class="block text-sm font-medium text-gray-700 mb-2">
                                Problem / tag
                            </label>

                            <select
                                id="tag"
                                name="tag"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
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
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                Miasto
                            </label>

                            <select
                                id="city"
                                name="city"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
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

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <input
                                type="checkbox"
                                name="for_children"
                                value="1"
                                @checked(request()->boolean('for_children'))
                                class="rounded border-gray-300 text-blue-600 shadow-sm"
                            >

                            <span class="text-sm font-medium text-gray-700">
                                Przyjmuje dzieci
                            </span>
                        </label>
                    </div>

                    <div class="flex flex-wrap gap-4 pt-2">
                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; padding: 9px 18px; background: #2563eb; color: white; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer;"
                        >
                            Filtruj
                        </button>

                        <a
                            href="{{ route('home') }}"
                            class="inline-flex items-center px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            @if ($doctors->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($doctors as $doctor)
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div style="width: 72px; height: 72px; border-radius: 9999px; overflow: hidden; background: #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    @if ($doctor->photo_url)
                                        <img
                                            src="{{ asset($doctor->photo_url) }}"
                                            alt="Zdjęcie lekarza"
                                            style="width: 72px; height: 72px; object-fit: cover; object-position: center 20%; display: block;"
                                        >
                                    @else
                                        <span class="text-gray-500 text-lg font-bold">
                                            {{ mb_substr($doctor->first_name, 0, 1) }}{{ mb_substr($doctor->last_name, 0, 1) }}
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $doctor->first_name }} {{ $doctor->last_name }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        @forelse ($doctor->specializations as $specialization)
                                            {{ $specialization->specialization_name }}@if (!$loop->last), @endif
                                        @empty
                                            Brak specjalizacji
                                        @endforelse
                                    </p>
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm mb-4">
                                {{ \Illuminate\Support\Str::limit($doctor->bio, 120) }}
                            </p>

                            <div class="mb-4">
                                <p class="text-sm font-semibold text-gray-700 mb-1">
                                    Kliniki:
                                </p>

                                @forelse ($doctor->clinics as $clinic)
                                    <p class="text-sm text-gray-600">
                                        {{ $clinic->name }}, {{ $clinic->city }}
                                    </p>
                                @empty
                                    <p class="text-sm text-gray-500">
                                        Brak przypisanej kliniki
                                    </p>
                                @endforelse
                            </div>

                            <div class="mb-4 flex flex-wrap gap-2">
                                @foreach ($doctor->helpTags as $tag)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">
                                        {{ $tag->tag_name }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    @if ($doctor->is_for_children)
                                        Dorośli i dzieci
                                    @else
                                        Tylko dorośli
                                    @endif
                                </div>

                                <a
                                    href="{{ route('doctors.show', $doctor) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                                >
                                    Zobacz profil
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $doctors->links() }}
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-gray-600">
                        Brak lekarzy do wyświetlenia.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
