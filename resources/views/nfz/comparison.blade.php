<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Porównanie NFZ
            </h2>

            <a href="{{ route('home') }}" style="font-size: 14px; color: #2563eb; font-weight: 700; text-decoration: none;">
                ← Wróć do strony głównej
            </a>
        </div>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1280px; margin: 0 auto;">

            @if ($errors->any())
                <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 0 0 6px 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #16a34a; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                    Moduł NFZ
                </p>

                <h1 style="font-size: 34px; font-weight: 900; color: #111827; margin-bottom: 12px;">
                    Porównaj termin prywatny z terminem NFZ
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 900px;">
                    Wybierz świadczenie NFZ, miasto, województwo i typ przypadku.
                    System porówna najbliższy termin publiczny z API NFZ z najbliższym prywatnym terminem dostępnym w aplikacji.
                </p>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Dane do porównania
                </h2>

                <p style="font-size: 14px; color: #6b7280; line-height: 1.7; margin-bottom: 24px;">
                    Pole „Świadczenie NFZ” używa oficjalnej nazwy wysyłanej do API NFZ.
                    Dla prywatnych terminów system używa uproszczonego hasła, np. „dermatolog”.
                </p>

                <form method="GET" action="{{ route('nfz.compare') }}" style="display: flex; flex-direction: column; gap: 24px;">
                    <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label for="benefit" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Świadczenie NFZ
                            </label>

                            <select
                                id="benefit"
                                name="benefit"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                                @foreach ($benefits as $benefitKey => $benefitData)
                                    <option value="{{ $benefitKey }}" @selected(request('benefit', 'kardiolog') === $benefitKey)>
                                        {{ $benefitData['label'] }} — {{ $benefitData['nfz_display'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="locality" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Miasto
                            </label>

                            <input
                                type="text"
                                id="locality"
                                name="locality"
                                value="{{ request('locality', 'Rzeszów') }}"
                                placeholder="np. Rzeszów"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="province" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Województwo NFZ
                            </label>

                            <select
                                id="province"
                                name="province"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
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
                            <label for="case" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Typ przypadku
                            </label>

                            <select
                                id="case"
                                name="case"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
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

                    <div style="display: flex; align-items: center; gap: 18px;">
                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 26px; background: #16a34a; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 8px 16px rgba(22, 163, 74, 0.18);"
                        >
                            Porównaj terminy
                        </button>

                        <a
                            href="{{ route('nfz.comparison') }}"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 26px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 12px; text-decoration: none;"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            @if ($searched)
                <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px; margin-bottom: 28px;">
                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h2 style="font-size: 20px; font-weight: 900; color: #111827; margin-bottom: 18px;">
                            Najbliższy termin prywatny
                        </h2>

                        @if ($privateSlot)
                            <p style="font-size: 14px; color: #374151; margin-bottom: 8px;">
                                <strong>Data:</strong>
                                {{ \Carbon\Carbon::parse($privateSlot->start_time)->format('d.m.Y H:i') }}
                            </p>

                            <p style="font-size: 14px; color: #374151; margin-bottom: 8px;">
                                <strong>Lekarz:</strong>
                                Dr {{ $privateSlot->doctor_first_name }} {{ $privateSlot->doctor_last_name }}
                            </p>

                            <p style="font-size: 14px; color: #374151; margin-bottom: 8px;">
                                <strong>Usługa:</strong>
                                {{ $privateSlot->service_name }}
                            </p>

                            <p style="font-size: 14px; color: #374151; margin-bottom: 8px;">
                                <strong>Klinika:</strong>
                                {{ $privateSlot->clinic_name }}, {{ $privateSlot->clinic_city }}
                            </p>

                            <p style="font-size: 14px; color: #374151; margin-bottom: 16px;">
                                <strong>Cena:</strong>
                                {{ number_format($privateSlot->service_price, 2) }} zł
                            </p>

                            <a
                                href="{{ route('doctors.show', $privateSlot->doctor_id) }}"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 9px 16px; background: #2563eb; color: white; font-size: 13px; font-weight: 900; border-radius: 10px; text-decoration: none;"
                            >
                                Przejdź do lekarza
                            </a>
                        @else
                            <p style="font-size: 14px; color: #6b7280;">
                                Brak pasującego prywatnego terminu w systemie.
                            </p>
                        @endif
                    </div>

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h2 style="font-size: 20px; font-weight: 900; color: #111827; margin-bottom: 18px;">
                            Najbliższy termin NFZ
                        </h2>

                        @if (! $nfzResult['success'])
                            <p style="font-size: 14px; color: #b91c1c;">
                                {{ $nfzResult['message'] }}
                            </p>
                        @elseif ($nfzResult['nearest'])
                            @php
                                $nearest = $nfzResult['nearest'];
                            @endphp

                            <p style="font-size: 14px; color: #374151; margin-bottom: 8px;">
                                <strong>Data:</strong>
                                {{ \Carbon\Carbon::parse($nearest['date'])->format('d.m.Y') }}
                            </p>

                            <p style="font-size: 14px; color: #374151; margin-bottom: 8px;">
                                <strong>Świadczenie:</strong>
                                {{ $nearest['benefit'] }}
                            </p>

                            <p style="font-size: 14px; color: #374151; margin-bottom: 8px;">
                                <strong>Placówka:</strong>
                                {{ $nearest['provider'] }}
                            </p>

                            <p style="font-size: 14px; color: #374151; margin-bottom: 8px;">
                                <strong>Adres:</strong>
                                {{ $nearest['address'] }}, {{ $nearest['locality'] }}
                            </p>

                            @if ($nearest['phone'])
                                <p style="font-size: 14px; color: #374151; margin-bottom: 8px;">
                                    <strong>Telefon:</strong>
                                    {{ $nearest['phone'] }}
                                </p>
                            @endif

                            @if ($nearest['waiting_count'] !== null)
                                <p style="font-size: 14px; color: #374151; margin-bottom: 8px;">
                                    <strong>Liczba oczekujących:</strong>
                                    {{ $nearest['waiting_count'] }}
                                </p>
                            @endif
                        @else
                            <p style="font-size: 14px; color: #6b7280;">
                                Brak znalezionych terminów NFZ dla podanych danych.
                            </p>
                        @endif
                    </div>

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h2 style="font-size: 20px; font-weight: 900; color: #111827; margin-bottom: 18px;">
                            Wynik porównania
                        </h2>

                        @if ($privateSlot && $nfzResult['nearest'] && $differenceDays !== null)
                            @if ($differenceDays > 0)
                                <p style="font-size: 20px; color: #15803d; font-weight: 900;">
                                    Prywatnie szybciej o {{ $differenceDays }} dni.
                                </p>
                            @elseif ($differenceDays < 0)
                                <p style="font-size: 20px; color: #1d4ed8; font-weight: 900;">
                                    NFZ szybciej o {{ abs($differenceDays) }} dni.
                                </p>
                            @else
                                <p style="font-size: 20px; color: #374151; font-weight: 900;">
                                    Terminy są tego samego dnia.
                                </p>
                            @endif
                        @else
                            <p style="font-size: 14px; color: #6b7280;">
                                Nie można policzyć różnicy, ponieważ brakuje terminu prywatnego albo terminu NFZ.
                            </p>
                        @endif

                        @if ($nfzResult['success'] && count($nfzResult['items']) > 0)
                            <p style="font-size: 12px; color: #6b7280; margin-top: 16px;">
                                API NFZ zwróciło {{ count($nfzResult['items']) }} wyników. Poniżej pokazano listę najbliższych terminów.
                            </p>
                        @endif
                    </div>
                </div>

                @if ($nfzResult['success'] && count($nfzResult['items']) > 0)
                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <div style="padding: 26px 30px; border-bottom: 1px solid #e5e7eb;">
                            <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                                Wyniki NFZ
                            </h2>

                            <p style="font-size: 14px; color: #6b7280;">
                                Lista terminów zwróconych przez API NFZ.
                            </p>
                        </div>

                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                                <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                    <tr>
                                        <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Data</th>
                                        <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Placówka</th>
                                        <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Miejscowość</th>
                                        <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">Oczekujących</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($nfzResult['items'] as $item)
                                        <tr style="border-bottom: 1px solid #f3f4f6;">
                                            <td style="padding: 18px; font-size: 14px; color: #111827; font-weight: 800;">
                                                {{ \Carbon\Carbon::parse($item['date'])->format('d.m.Y') }}
                                            </td>

                                            <td style="padding: 18px; font-size: 14px; color: #374151;">
                                                <strong>{{ $item['provider'] }}</strong>
                                                <br>
                                                <span style="font-size: 12px; color: #6b7280;">
                                                    {{ $item['place'] }}
                                                </span>
                                            </td>

                                            <td style="padding: 18px; font-size: 14px; color: #374151;">
                                                {{ $item['locality'] }}
                                                <br>
                                                <span style="font-size: 12px; color: #6b7280;">
                                                    {{ $item['address'] }}
                                                </span>
                                            </td>

                                            <td style="padding: 18px; font-size: 14px; color: #374151;">
                                                {{ $item['waiting_count'] ?? 'Brak danych' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
