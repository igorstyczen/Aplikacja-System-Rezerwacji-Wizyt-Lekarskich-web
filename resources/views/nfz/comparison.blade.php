<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Porównanie terminów prywatnych i NFZ
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-6">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Porównanie dostępności terminów
                </h1>

                <p class="text-gray-600 mt-2">
                    Moduł pobiera publiczne dane z API NFZ i porównuje najbliższy termin refundowany
                    z najbliższym prywatnym terminem dostępnym w systemie.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <form method="GET" action="{{ route('nfz.compare') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="benefit" class="block text-sm font-medium text-gray-700 mb-1">
                                Świadczenie / usługa
                            </label>

                            <input
                                type="text"
                                id="benefit"
                                name="benefit"
                                value="{{ request('benefit', 'poradnia kardiologiczna') }}"
                                placeholder="np. poradnia kardiologiczna"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                required
                            >

                            <p class="text-xs text-gray-500 mt-1">
                                Wpisz nazwę świadczenia NFZ albo podobną nazwę prywatnej usługi.
                            </p>
                        </div>

                        <div>
                            <label for="locality" class="block text-sm font-medium text-gray-700 mb-1">
                                Miasto
                            </label>

                            <input
                                type="text"
                                id="locality"
                                name="locality"
                                value="{{ request('locality', 'Rzeszów') }}"
                                placeholder="np. Rzeszów"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                required
                            >
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700 mb-1">
                                Województwo NFZ
                            </label>

                            <select
                                id="province"
                                name="province"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                required
                            >
                                <option value="01" @selected(request('province') === '01')>Dolnośląskie</option>
                                <option value="02" @selected(request('province') === '02')>Kujawsko-pomorskie</option>
                                <option value="03" @selected(request('province') === '03')>Lubelskie</option>
                                <option value="04" @selected(request('province') === '04')>Lubuskie</option>
                                <option value="05" @selected(request('province') === '05')>Łódzkie</option>
                                <option value="06" @selected(request('province') === '06')>Małopolskie</option>
                                <option value="07" @selected(request('province') === '07')>Mazowieckie</option>
                                <option value="08" @selected(request('province') === '08')>Opolskie</option>
                                <option value="09" @selected(request('province', '09') === '09')>Podkarpackie</option>
                                <option value="10" @selected(request('province') === '10')>Podlaskie</option>
                                <option value="11" @selected(request('province') === '11')>Pomorskie</option>
                                <option value="12" @selected(request('province') === '12')>Śląskie</option>
                                <option value="13" @selected(request('province') === '13')>Świętokrzyskie</option>
                                <option value="14" @selected(request('province') === '14')>Warmińsko-mazurskie</option>
                                <option value="15" @selected(request('province') === '15')>Wielkopolskie</option>
                                <option value="16" @selected(request('province') === '16')>Zachodniopomorskie</option>
                            </select>
                        </div>

                        <div>
                            <label for="case" class="block text-sm font-medium text-gray-700 mb-1">
                                Typ przypadku
                            </label>

                            <select
                                id="case"
                                name="case"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                required
                            >
                                <option value="1" @selected(request('case', '1') === '1')>
                                    Stabilny
                                </option>
                                <option value="2" @selected(request('case') === '2')>
                                    Pilny
                                </option>
                            </select>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                    >
                        Porównaj terminy
                    </button>
                </form>
            </div>

            @if ($searched)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Najbliższy termin prywatny
                        </h2>

                        @if ($privateSlot)
                            <div class="space-y-2 text-sm text-gray-700">
                                <p>
                                    <strong>Data:</strong>
                                    {{ \Carbon\Carbon::parse($privateSlot->start_time)->format('d.m.Y H:i') }}
                                </p>

                                <p>
                                    <strong>Lekarz:</strong>
                                    Dr {{ $privateSlot->doctor_first_name }} {{ $privateSlot->doctor_last_name }}
                                </p>

                                <p>
                                    <strong>Usługa:</strong>
                                    {{ $privateSlot->service_name }}
                                </p>

                                <p>
                                    <strong>Klinika:</strong>
                                    {{ $privateSlot->clinic_name }}, {{ $privateSlot->clinic_city }}
                                </p>

                                <p>
                                    <strong>Cena:</strong>
                                    {{ number_format($privateSlot->service_price, 2) }} zł
                                </p>

                                <p>
                                    <strong>Czas trwania:</strong>
                                    {{ $privateSlot->service_duration }} min
                                </p>
                            </div>
                        @else
                            <p class="text-gray-600">
                                Brak pasującego prywatnego terminu w systemie.
                            </p>
                        @endif
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Najbliższy termin NFZ
                        </h2>

                        @if (! $nfzResult['success'])
                            <p class="text-red-700">
                                {{ $nfzResult['message'] }}
                            </p>
                        @elseif ($nfzResult['nearest'])
                            @php
                                $nearest = $nfzResult['nearest'];
                            @endphp

                            <div class="space-y-2 text-sm text-gray-700">
                                <p>
                                    <strong>Data:</strong>
                                    {{ \Carbon\Carbon::parse($nearest['date'])->format('d.m.Y') }}
                                </p>

                                <p>
                                    <strong>Świadczenie:</strong>
                                    {{ $nearest['benefit'] }}
                                </p>

                                <p>
                                    <strong>Placówka:</strong>
                                    {{ $nearest['provider'] }}
                                </p>

                                <p>
                                    <strong>Miejsce:</strong>
                                    {{ $nearest['place'] }}
                                </p>

                                <p>
                                    <strong>Adres:</strong>
                                    {{ $nearest['address'] }}, {{ $nearest['locality'] }}
                                </p>

                                @if ($nearest['phone'])
                                    <p>
                                        <strong>Telefon:</strong>
                                        {{ $nearest['phone'] }}
                                    </p>
                                @endif

                                @if ($nearest['waiting_count'] !== null)
                                    <p>
                                        <strong>Liczba oczekujących:</strong>
                                        {{ $nearest['waiting_count'] }}
                                    </p>
                                @endif

                                @if ($nearest['average_waiting_days'] !== null)
                                    <p>
                                        <strong>Średni czas oczekiwania:</strong>
                                        {{ $nearest['average_waiting_days'] }} dni
                                    </p>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-600">
                                Brak znalezionych terminów NFZ dla podanych danych.
                            </p>
                        @endif
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Wynik porównania
                    </h2>

                    @if ($privateSlot && $nfzResult['nearest'] && $differenceDays !== null)
                        @if ($differenceDays > 0)
                            <p class="text-green-700 font-semibold">
                                Prywatny termin jest szybszy o {{ $differenceDays }} dni.
                            </p>
                        @elseif ($differenceDays < 0)
                            <p class="text-yellow-700 font-semibold">
                                Termin NFZ jest szybszy o {{ abs($differenceDays) }} dni.
                            </p>
                        @else
                            <p class="text-blue-700 font-semibold">
                                Termin prywatny i NFZ wypadają tego samego dnia.
                            </p>
                        @endif
                    @else
                        <p class="text-gray-600">
                            Nie można policzyć różnicy, ponieważ brakuje terminu prywatnego albo terminu NFZ.
                        </p>
                    @endif
                </div>

                @if ($nfzResult['success'] && count($nfzResult['items']) > 0)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Wyniki NFZ
                            </h2>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Data
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Placówka
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Miejscowość
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Oczekujących
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($nfzResult['items'] as $item)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($item['date'])->format('d.m.Y') }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $item['provider'] }}
                                            <br>
                                            <span class="text-gray-500">
                                                {{ $item['place'] }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $item['locality'] }}
                                            <br>
                                            <span class="text-gray-500">
                                                {{ $item['address'] }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $item['waiting_count'] ?? 'Brak danych' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
