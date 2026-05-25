<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profil lekarza
            </h2>

            <a href="{{ route('home') }}" style="font-size: 14px; color: #2563eb; font-weight: 700; text-decoration: none;">
                ← Wróć do listy lekarzy
            </a>
        </div>
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
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach ($errors->all() as $error)
                            <li style="margin-bottom: 4px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <div style="display: flex; align-items: flex-start; gap: 30px;">
                    <div style="width: 128px; height: 128px; border-radius: 9999px; overflow: hidden; background: #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        @if ($doctor->photo_url)
                            <img
                                src="{{ asset($doctor->photo_url) }}"
                                alt="Zdjęcie lekarza"
                                style="width: 128px; height: 128px; object-fit: cover; object-position: center 20%; display: block;"
                            >
                        @else
                            <span style="color: #6b7280; font-size: 34px; font-weight: 900;">
                                {{ mb_substr($doctor->first_name, 0, 1) }}{{ mb_substr($doctor->last_name, 0, 1) }}
                            </span>
                        @endif
                    </div>

                    <div style="flex: 1;">
                        <p style="color: #2563eb; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                            Profil lekarza
                        </p>

                        <h1 style="font-size: 34px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                            Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                        </h1>

                        <p style="font-size: 16px; color: #4b5563; margin-bottom: 16px;">
                            @forelse ($doctor->specializations as $specialization)
                                {{ $specialization->specialization_name }}@if (!$loop->last), @endif
                            @empty
                                Brak specjalizacji
                            @endforelse
                        </p>

                        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px;">
                            @forelse ($doctor->helpTags as $tag)
                                <span style="padding: 7px 11px; background: #eff6ff; color: #1d4ed8; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                    {{ $tag->tag_name }}
                                </span>
                            @empty
                                <span style="padding: 7px 11px; background: #f3f4f6; color: #6b7280; border-radius: 999px; font-size: 12px; font-weight: 800;">
                                    Brak tagów pomocy
                                </span>
                            @endforelse
                        </div>

                        <p style="color: #374151; font-size: 15px; line-height: 1.8; max-width: 900px;">
                            {{ $doctor->bio ?? 'Brak opisu lekarza.' }}
                        </p>

                        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px;">
                            @if ($doctor->is_for_adults)
                                <span style="padding: 7px 12px; background: #ecfdf5; color: #047857; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                    Przyjmuje dorosłych
                                </span>
                            @endif

                            @if ($doctor->is_for_children)
                                <span style="padding: 7px 12px; background: #f0fdf4; color: #15803d; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                    Przyjmuje dzieci
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: minmax(0, 1fr) 420px; gap: 28px; align-items: start;">

                <div style="display: flex; flex-direction: column; gap: 28px;">
                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 30px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h3 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Kliniki
                        </h3>

                        <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
                            Miejsca, w których lekarz przyjmuje pacjentów.
                        </p>

                        @forelse ($doctor->clinics as $clinic)
                            <div style="padding: 18px 0; border-bottom: 1px solid #f3f4f6;">
                                <h4 style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                                    {{ $clinic->name }}
                                </h4>

                                <p style="font-size: 14px; color: #4b5563; margin-bottom: 4px;">
                                    {{ $clinic->address }}, {{ $clinic->city }}
                                </p>

                                @if ($clinic->details)
                                    <p style="font-size: 13px; color: #6b7280; line-height: 1.6;">
                                        {{ $clinic->details }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p style="color: #6b7280;">
                                Brak przypisanych klinik.
                            </p>
                        @endforelse
                    </div>

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 30px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h3 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Usługi
                        </h3>

                        <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
                            Dostępne usługi, ceny i orientacyjny czas trwania.
                        </p>

                        @forelse ($doctor->services as $service)
                            <div style="padding: 20px 0; border-bottom: 1px solid #f3f4f6;">
                                <div style="display: flex; justify-content: space-between; gap: 24px;">
                                    <div>
                                        <h4 style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                                            {{ $service->name }}
                                        </h4>

                                        @if ($service->description)
                                            <p style="font-size: 14px; color: #4b5563; line-height: 1.6; margin-bottom: 8px;">
                                                {{ $service->description }}
                                            </p>
                                        @endif

                                        <p style="font-size: 13px; color: #6b7280;">
                                            {{ $service->clinic->name ?? 'Brak kliniki' }}
                                        </p>
                                    </div>

                                    <div style="text-align: right; flex-shrink: 0;">
                                        <p style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                                            {{ number_format($service->price, 2) }} zł
                                        </p>

                                        <span style="padding: 6px 10px; background: #eff6ff; color: #1d4ed8; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                            {{ $service->duration_minutes }} min
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p style="color: #6b7280;">
                                Brak usług.
                            </p>
                        @endforelse
                    </div>

                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 30px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h3 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Opinie
                        </h3>

                        <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
                            Opinie pacjentów po zakończonych wizytach.
                        </p>

                        @forelse ($doctor->reviews as $review)
                            <div style="padding: 18px 0; border-bottom: 1px solid #f3f4f6;">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 10px;">
                                    <p style="font-weight: 900; color: #111827;">
                                        Ocena: {{ $review->rating }}/5
                                    </p>

                                    <p style="font-size: 13px; color: #6b7280;">
                                        {{ $review->created_at->format('d.m.Y') }}
                                    </p>
                                </div>

                                @if ($review->comment)
                                    <p style="font-size: 14px; color: #4b5563; line-height: 1.7;">
                                        {{ $review->comment }}
                                    </p>
                                @endif

                                @if ($review->images->count() > 0)
                                    <div style="margin-top: 16px; display: flex; flex-wrap: wrap; gap: 12px;">
                                        @foreach ($review->images as $image)
                                            <a href="{{ asset($image->image) }}" target="_blank">
                                                <img
                                                    src="{{ asset($image->image) }}"
                                                    alt="Zdjęcie dodane do opinii"
                                                    style="width: 120px; height: 120px; object-fit: cover; border-radius: 14px; border: 1px solid #e5e7eb;"
                                                >
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p style="color: #6b7280;">
                                Brak opinii.
                            </p>
                        @endforelse
                    </div>
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 30px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06); position: sticky; top: 24px;">
                    <h3 style="font-size: 24px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Umów wizytę
                    </h3>

                    <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-bottom: 24px;">
                        Wybierz usługę i dostępny termin. Po rezerwacji przejdziesz do płatności.
                    </p>

                    @if ($doctor->services->count() > 0 && $availabilitySlots->count() > 0)
                        <form method="POST" action="{{ route('appointments.store') }}" id="bookingForm">
                            @csrf

                            <div style="margin-bottom: 24px;">
                                <label for="service_id" style="display: block; font-size: 14px; font-weight: 800; color: #374151; margin-bottom: 8px;">
                                    Wybierz usługę
                                </label>

                                <select
                                    id="service_id"
                                    name="service_id"
                                    required
                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 12px 14px; font-size: 14px;"
                                >
                                    <option value="">Wybierz usługę</option>

                                    @foreach ($doctor->services as $service)
                                        <option
                                            value="{{ $service->id }}"
                                            data-clinic-id="{{ $service->clinic_id }}"
                                        >
                                            {{ $service->name }} — {{ number_format($service->price, 2) }} zł — {{ $service->duration_minutes }} min
                                            @if ($service->clinic)
                                                — {{ $service->clinic->name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 20px;">
                                <div>
                                    <h4 style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 4px;">
                                        Wybierz termin
                                    </h4>

                                    <p style="font-size: 13px; color: #6b7280;">
                                        {{ $weekStart->format('d.m.Y') }} - {{ $weekEnd->format('d.m.Y') }}
                                    </p>
                                </div>

                                <div style="display: flex; gap: 8px;">
                                    @if ($week > 0)
                                        <a
                                            href="{{ route('doctors.show', ['doctor' => $doctor, 'week' => $week - 1]) }}"
                                            style="padding: 8px 11px; background: #f3f4f6; color: #374151; border-radius: 10px; font-size: 12px; font-weight: 900; text-decoration: none;"
                                        >
                                            ←
                                        </a>
                                    @endif

                                    @if ($week < 4)
                                        <a
                                            href="{{ route('doctors.show', ['doctor' => $doctor, 'week' => $week + 1]) }}"
                                            style="padding: 8px 11px; background: #dbeafe; color: #1d4ed8; border-radius: 10px; font-size: 12px; font-weight: 900; text-decoration: none;"
                                        >
                                            →
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 14px;">
                                @foreach ($availabilitySlots as $date => $slots)
                                    @php
                                        $day = \Carbon\Carbon::parse($date);
                                        $dayId = 'day-' . $day->format('Ymd');
                                    @endphp

                                    <div class="day-card" style="border: 1px solid #e5e7eb; border-radius: 16px; padding: 16px; background: #f9fafb;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px;">
                                            <div>
                                                <p style="font-size: 14px; font-weight: 900; color: #111827;">
                                                    {{ ucfirst($day->locale('pl')->isoFormat('dddd')) }}
                                                </p>

                                                <p style="font-size: 12px; color: #6b7280;">
                                                    {{ $day->format('d.m') }}
                                                </p>
                                            </div>

                                            <span style="font-size: 12px; color: #6b7280; font-weight: 800;">
                                                {{ $slots->count() }} terminów
                                            </span>
                                        </div>

                                        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px;">
                                            @foreach ($slots as $index => $slot)
                                                <label
                                                    class="slot-option"
                                                    data-clinic-id="{{ $slot->clinic_id }}"
                                                    data-extra="{{ $index >= 6 ? '1' : '0' }}"
                                                    data-day-id="{{ $dayId }}"
                                                    style="{{ $index >= 6 ? 'display: none;' : 'display: block;' }} cursor: pointer;"
                                                >
                                                    <input
                                                        type="radio"
                                                        name="slot_id"
                                                        value="{{ $slot->id }}"
                                                        required
                                                        style="position: absolute; opacity: 0; pointer-events: none;"
                                                    >

                                                    <div
                                                        class="slot-button"
                                                        style="text-align: center; padding: 9px 8px; border-radius: 999px; background: #dcfce7; color: #064e3b; font-size: 13px; font-weight: 900; border: 2px solid transparent;"
                                                    >
                                                        {{ $slot->start_time->format('H:i') }}
                                                    </div>

                                                    <p style="font-size: 10px; color: #6b7280; text-align: center; margin-top: 4px; line-height: 1.2;">
                                                        {{ $slot->clinic->name ?? 'Klinika' }}
                                                    </p>
                                                </label>
                                            @endforeach
                                        </div>

                                        @if ($slots->count() > 6)
                                            <button
                                                type="button"
                                                class="show-more-slots"
                                                data-day-id="{{ $dayId }}"
                                                style="width: 100%; margin-top: 12px; padding: 8px 10px; background: #ecfdf5; color: #047857; border: none; border-radius: 10px; font-size: 12px; font-weight: 900; cursor: pointer;"
                                            >
                                                Pokaż więcej godzin
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div style="margin-top: 24px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
                                <p style="font-size: 13px; color: #6b7280; line-height: 1.6; margin-bottom: 16px;">
                                    Po kliknięciu system wstępnie zablokuje wybrany termin i przeniesie Cię do płatności.
                                </p>

                                <button
                                    type="submit"
                                    style="width: 100%; padding: 13px 24px; background: #16a34a; color: white; border: none; border-radius: 12px; font-size: 15px; font-weight: 900; cursor: pointer; box-shadow: 0 10px 20px rgba(22, 163, 74, 0.22);"
                                >
                                    Zarezerwuj wizytę
                                </button>
                            </div>
                        </form>
                    @elseif ($doctor->services->count() === 0)
                        <p style="color: #6b7280;">
                            Ten lekarz nie ma jeszcze dodanych usług.
                        </p>
                    @else
                        <div>
                            <p style="color: #6b7280; margin-bottom: 16px;">
                                Brak wolnych terminów w tym tygodniu.
                            </p>

                            <div style="display: flex; gap: 10px;">
                                @if ($week > 0)
                                    <a
                                        href="{{ route('doctors.show', ['doctor' => $doctor, 'week' => $week - 1]) }}"
                                        style="padding: 10px 14px; background: #f3f4f6; color: #374151; border-radius: 10px; font-size: 13px; font-weight: 900; text-decoration: none;"
                                    >
                                        ← Poprzedni
                                    </a>
                                @endif

                                @if ($week < 4)
                                    <a
                                        href="{{ route('doctors.show', ['doctor' => $doctor, 'week' => $week + 1]) }}"
                                        style="padding: 10px 14px; background: #dbeafe; color: #1d4ed8; border-radius: 10px; font-size: 13px; font-weight: 900; text-decoration: none;"
                                    >
                                        Następny →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const serviceSelect = document.getElementById('service_id');
            const slotOptions = document.querySelectorAll('.slot-option');
            const bookingForm = document.getElementById('bookingForm');

            function clearSelectedSlots() {
                document.querySelectorAll('.slot-button').forEach(function (button) {
                    button.style.background = '#dcfce7';
                    button.style.color = '#064e3b';
                    button.style.borderColor = 'transparent';
                });
            }

            slotOptions.forEach(function (slotOption) {
                slotOption.addEventListener('click', function () {
                    if (slotOption.dataset.availableForService === '0') {
                        return;
                    }

                    const input = slotOption.querySelector('input[type="radio"]');
                    const button = slotOption.querySelector('.slot-button');

                    input.checked = true;

                    clearSelectedSlots();

                    button.style.background = '#059669';
                    button.style.color = 'white';
                    button.style.borderColor = '#047857';
                });
            });

            function filterSlotsByService() {
                if (!serviceSelect) {
                    return;
                }

                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                const selectedClinicId = selectedOption ? selectedOption.dataset.clinicId : null;

                clearSelectedSlots();

                slotOptions.forEach(function (slotOption) {
                    const slotClinicId = slotOption.dataset.clinicId;
                    const isExtra = slotOption.dataset.extra === '1';
                    const input = slotOption.querySelector('input[type="radio"]');

                    input.checked = false;

                    if (!selectedClinicId || selectedClinicId === slotClinicId) {
                        slotOption.dataset.availableForService = '1';

                        if (!isExtra || slotOption.dataset.expanded === '1') {
                            slotOption.style.display = 'block';
                        }
                    } else {
                        slotOption.dataset.availableForService = '0';
                        slotOption.style.display = 'none';
                    }
                });
            }

            if (serviceSelect) {
                serviceSelect.addEventListener('change', filterSlotsByService);
                filterSlotsByService();
            }

            document.querySelectorAll('.show-more-slots').forEach(function (button) {
                button.addEventListener('click', function () {
                    const dayCard = button.closest('.day-card');
                    const hiddenSlots = dayCard.querySelectorAll('.slot-option[data-extra="1"]');

                    const isExpanded = button.dataset.expanded === '1';

                    hiddenSlots.forEach(function (slotOption) {
                        if (isExpanded) {
                            slotOption.dataset.expanded = '0';
                            slotOption.style.display = 'none';
                        } else {
                            slotOption.dataset.expanded = '1';

                            if (slotOption.dataset.availableForService !== '0') {
                                slotOption.style.display = 'block';
                            }
                        }
                    });

                    if (isExpanded) {
                        button.dataset.expanded = '0';
                        button.textContent = 'Pokaż więcej godzin';
                    } else {
                        button.dataset.expanded = '1';
                        button.textContent = 'Pokaż mniej godzin';
                    }
                });
            });

            if (bookingForm) {
                bookingForm.addEventListener('submit', function (event) {
                    const serviceId = serviceSelect.value;
                    const selectedSlot = bookingForm.querySelector('input[name="slot_id"]:checked');

                    if (!serviceId) {
                        event.preventDefault();
                        alert('Najpierw wybierz usługę.');
                        return;
                    }

                    if (!selectedSlot) {
                        event.preventDefault();
                        alert('Najpierw wybierz termin wizyty.');
                    }
                });
            }
        });
    </script>
</x-app-layout>
