<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mój grafik lekarza
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

            @if ($message)
                <div style="background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    {{ $message }}
                </div>
            @endif

            @if ($doctor)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                    <p style="color: #059669; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                        Panel lekarza
                    </p>

                    <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                        Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </h1>

                    <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 850px;">
                        Tutaj możesz dodawać wolne terminy przyjęć, ustawiać godziny wizyt,
                        wybierać klinikę oraz zarządzać swoim grafikiem.
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Dodaj wolne terminy
                    </h2>

                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 26px;">
                        Wybierz klinikę, dzień oraz zakres godzin. System automatycznie podzieli zakres na pojedyncze terminy.
                    </p>

                    @if ($clinics->count() > 0)
                        <form method="POST" action="{{ route('doctor.schedule.store') }}" style="display: flex; flex-direction: column; gap: 24px;">
                            @csrf

                            <div style="display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 20px;">
                                <div>
                                    <label for="clinic_id" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                        Klinika
                                    </label>

                                    <select
                                        id="clinic_id"
                                        name="clinic_id"
                                        required
                                        style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                    >
                                        <option value="">Wybierz klinikę</option>

                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->id }}" @selected(old('clinic_id') == $clinic->id)>
                                                {{ $clinic->name }} — {{ $clinic->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="date" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                        Data
                                    </label>

                                    <input
                                        type="date"
                                        id="date"
                                        name="date"
                                        value="{{ old('date') }}"
                                        required
                                        style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 10px 14px; font-size: 14px;"
                                    >
                                </div>

                                <div>
                                    <label for="start_time" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                        Godzina od
                                    </label>

                                    <input
                                        type="time"
                                        id="start_time"
                                        name="start_time"
                                        value="{{ old('start_time') }}"
                                        required
                                        style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 10px 14px; font-size: 14px;"
                                    >
                                </div>

                                <div>
                                    <label for="end_time" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                        Godzina do
                                    </label>

                                    <input
                                        type="time"
                                        id="end_time"
                                        name="end_time"
                                        value="{{ old('end_time') }}"
                                        required
                                        style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 10px 14px; font-size: 14px;"
                                    >
                                </div>

                                <div>
                                    <label for="slot_duration" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                        Długość wizyty
                                    </label>

                                    <select
                                        id="slot_duration"
                                        name="slot_duration"
                                        required
                                        style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                    >
                                        <option value="15" @selected(old('slot_duration') == 15)>15 min</option>
                                        <option value="20" @selected(old('slot_duration') == 20)>20 min</option>
                                        <option value="30" @selected(old('slot_duration', 30) == 30)>30 min</option>
                                        <option value="45" @selected(old('slot_duration') == 45)>45 min</option>
                                        <option value="60" @selected(old('slot_duration') == 60)>60 min</option>
                                    </select>
                                </div>
                            </div>

                            <div style="padding: 16px 18px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 16px;">
                                <label style="display: flex; align-items: flex-start; gap: 14px; cursor: pointer; margin-bottom: 0;">
                                    <input
                                        type="checkbox"
                                        id="repeat_weekly"
                                        name="repeat_weekly"
                                        value="1"
                                        style="margin-top: 3px;"
                                        @checked(old('repeat_weekly'))
                                    >

                                    <div>
                                        <span style="display: block; font-size: 14px; font-weight: 800; color: #374151;">
                                            Powtarzaj co tydzień
                                        </span>

                                        <span style="display: block; font-size: 13px; color: #6b7280; margin-top: 4px; line-height: 1.5;">
                                            System utworzy ten sam zakres godzin w wybranym dniu tygodnia aż do wskazanej daty końcowej.
                                        </span>
                                    </div>
                                </label>

                                <div id="repeat_until_wrapper" style="margin-top: 14px; padding-left: 30px; {{ old('repeat_weekly') ? '' : 'display: none;' }}">
                                    <label for="repeat_until" style="display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px;">
                                        Powtarzaj do daty
                                    </label>

                                    <input
                                        type="date"
                                        id="repeat_until"
                                        name="repeat_until"
                                        value="{{ old('repeat_until') }}"
                                        min="{{ old('date', now()->format('Y-m-d')) }}"
                                        style="width: 100%; max-width: 260px; border: 1px solid #d1d5db; border-radius: 12px; padding: 10px 14px; font-size: 14px;"
                                    >

                                    <p style="font-size: 12px; color: #6b7280; margin-top: 8px; line-height: 1.5;">
                                        Domyślnie ustawiane na 3 miesiące do przodu. Możesz wydłużyć lub skrócić ten okres.
                                    </p>
                                </div>
                            </div>

                            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 16px; padding: 18px 20px;">
                                <p style="font-size: 14px; color: #1e40af; line-height: 1.7; margin: 0;">
                                    <strong>Przykład:</strong> jeśli wybierzesz 10:00–12:00 i długość 30 minut,
                                    system utworzy terminy: 10:00, 10:30, 11:00 i 11:30.
                                    Możesz dodać kilka zakresów jednego dnia, np. 10:00–14:00 w jednej klinice
                                    i 15:00–18:00 w drugiej.
                                </p>
                            </div>

                            <div style="display: flex; align-items: center; gap: 18px;">
                                <button
                                    type="submit"
                                    style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 26px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 8px 16px rgba(37, 99, 235, 0.18);"
                                >
                                    Dodaj terminy
                                </button>
                            </div>
                        </form>
                    @else
                        <div style="background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 16px 20px; border-radius: 14px;">
                            Brak klinik w systemie. Administrator musi najpierw dodać przynajmniej jedną klinikę.
                        </div>
                    @endif
                </div>
            @endif

            @if ($slots->count() > 0)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <div style="padding: 26px 30px; border-bottom: 1px solid #e5e7eb;">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Lista terminów
                        </h2>

                        <p style="font-size: 14px; color: #6b7280;">
                            Wolne, zarezerwowane i niedostępne terminy w Twoim grafiku.
                        </p>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                            <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Data
                                    </th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Godzina
                                    </th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Klinika
                                    </th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Status
                                    </th>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Akcje
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($slots as $slot)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 18px; font-size: 14px; color: #111827; font-weight: 800;">
                                            {{ $slot->start_time->format('d.m.Y') }}
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280;">
                                                {{ $slot->start_time->translatedFormat('l') }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151; white-space: nowrap;">
                                            {{ $slot->start_time->format('H:i') }}
                                            -
                                            {{ $slot->end_time->format('H:i') }}
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            <strong>{{ $slot->clinic->name ?? 'Brak kliniki' }}</strong>
                                            <br>
                                            <span style="font-size: 12px; color: #6b7280;">
                                                {{ $slot->clinic->city ?? '' }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            @if ($slot->status === 'available')
                                                <span style="padding: 6px 10px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Wolny
                                                </span>
                                            @elseif ($slot->status === 'booked')
                                                <span style="padding: 6px 10px; background: #dbeafe; color: #1d4ed8; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Zarezerwowany
                                                </span>
                                            @else
                                                <span style="padding: 6px 10px; background: #f3f4f6; color: #374151; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                    Niedostępny
                                                </span>
                                            @endif
                                        </td>

                                        <td style="padding: 18px; font-size: 14px;">
                                            @if ($slot->status === 'available')
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <a
                                                        href="{{ route('doctor.schedule.edit', $slot) }}"
                                                        style="display: inline-flex; align-items: center; justify-content: center; padding: 8px 14px; background: #dbeafe; color: #1d4ed8; border-radius: 9px; font-size: 12px; font-weight: 900; text-decoration: none;"
                                                    >
                                                        Edytuj
                                                    </a>

                                                    <form method="POST" action="{{ route('doctor.schedule.destroy', $slot) }}">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            onclick="return confirm('Czy na pewno chcesz usunąć ten wolny termin?')"
                                                            style="display: inline-flex; align-items: center; justify-content: center; padding: 8px 14px; background: #fee2e2; color: #b91c1c; border-radius: 9px; border: none; font-size: 12px; font-weight: 900; cursor: pointer;"
                                                        >
                                                            Usuń
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span style="font-size: 12px; color: #9ca3af; font-weight: 700;">
                                                    Brak akcji
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <p style="color: #6b7280;">
                        Brak terminów w grafiku.
                    </p>
                </div>
            @endif

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const repeatCheckbox = document.getElementById('repeat_weekly');
            const repeatUntilWrapper = document.getElementById('repeat_until_wrapper');
            const repeatUntilInput = document.getElementById('repeat_until');
            const dateInput = document.getElementById('date');

            function addMonthsToDate(dateStr, months) {
                if (!dateStr) {
                    return '';
                }

                const parts = dateStr.split('-');
                const date = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
                date.setMonth(date.getMonth() + months);

                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return year + '-' + month + '-' + day;
            }

            function ensureRepeatUntilDefault() {
                if (!repeatCheckbox || !repeatCheckbox.checked || !repeatUntilInput || !dateInput) {
                    return;
                }

                if (!repeatUntilInput.value && dateInput.value) {
                    repeatUntilInput.value = addMonthsToDate(dateInput.value, 3);
                }

                syncRepeatUntilMin();
            }

            function toggleRepeatUntil() {
                if (!repeatCheckbox || !repeatUntilWrapper) {
                    return;
                }

                const isChecked = repeatCheckbox.checked;
                repeatUntilWrapper.style.display = isChecked ? 'block' : 'none';

                if (repeatUntilInput) {
                    repeatUntilInput.required = isChecked;

                    if (!isChecked) {
                        repeatUntilInput.setCustomValidity('');
                    }
                }

                if (isChecked) {
                    ensureRepeatUntilDefault();
                }
            }

            function syncRepeatUntilMin() {
                if (repeatUntilInput && dateInput && dateInput.value) {
                    repeatUntilInput.min = dateInput.value;
                }
            }

            if (repeatCheckbox) {
                repeatCheckbox.addEventListener('change', toggleRepeatUntil);
                toggleRepeatUntil();
            }

            if (dateInput) {
                dateInput.addEventListener('change', function () {
                    syncRepeatUntilMin();

                    if (repeatCheckbox && repeatCheckbox.checked) {
                        repeatUntilInput.value = addMonthsToDate(dateInput.value, 3);
                    }
                });
                syncRepeatUntilMin();
            }

            const scheduleForm = document.querySelector('form[action="{{ route('doctor.schedule.store') }}"]');

            if (scheduleForm) {
                scheduleForm.addEventListener('submit', function (event) {
                    if (repeatCheckbox && repeatCheckbox.checked) {
                        ensureRepeatUntilDefault();

                        if (repeatUntilInput && !repeatUntilInput.value) {
                            event.preventDefault();
                            repeatUntilInput.setCustomValidity('Podaj datę końcową powtarzania.');
                            repeatUntilInput.reportValidity();
                            return;
                        }

                        if (repeatUntilInput) {
                            repeatUntilInput.setCustomValidity('');
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
