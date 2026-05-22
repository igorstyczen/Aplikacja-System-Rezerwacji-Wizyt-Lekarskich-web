<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profil lekarza
            </h2>

            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Wróć do listy lekarzy
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start gap-6">
                    <div style="width: 96px; height: 96px; border-radius: 9999px; overflow: hidden; background: #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        @if ($doctor->photo_url)
                            <img
                                src="{{ asset($doctor->photo_url) }}"
                                alt="Zdjęcie lekarza"
                                style="width: 96px; height: 96px; object-fit: cover; object-position: center 20%; display: block;"
                            >
                        @else
                            <span class="text-gray-500 text-2xl font-bold">
                                {{ mb_substr($doctor->first_name, 0, 1) }}{{ mb_substr($doctor->last_name, 0, 1) }}
                            </span>
                        @endif
                    </div>

                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $doctor->first_name }} {{ $doctor->last_name }}
                        </h1>

                        <p class="text-gray-600 mt-1">
                            @forelse ($doctor->specializations as $specialization)
                                {{ $specialization->specialization_name }}@if (!$loop->last), @endif
                            @empty
                                Brak specjalizacji
                            @endforelse
                        </p>

                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($doctor->helpTags as $tag)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">
                                    {{ $tag->tag_name }}
                                </span>
                            @endforeach
                        </div>

                        <p class="text-gray-700 mt-4">
                            {{ $doctor->bio ?? 'Brak opisu lekarza.' }}
                        </p>

                        <div class="mt-4 text-sm text-gray-600">
                            @if ($doctor->is_for_children)
                                Przyjmuje dorosłych i dzieci.
                            @else
                                Przyjmuje tylko dorosłych.
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Kliniki
                </h3>

                @forelse ($doctor->clinics as $clinic)
                    <div class="border-b border-gray-100 pb-4 mb-4 last:border-b-0 last:mb-0 last:pb-0">
                        <h4 class="font-semibold text-gray-800">
                            {{ $clinic->name }}
                        </h4>

                        <p class="text-sm text-gray-600">
                            {{ $clinic->address }}, {{ $clinic->city }}
                        </p>

                        @if ($clinic->details)
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $clinic->details }}
                            </p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">
                        Brak przypisanych klinik.
                    </p>
                @endforelse
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Usługi
                </h3>

                @forelse ($doctor->services as $service)
                    <div class="border-b border-gray-100 pb-4 mb-4 last:border-b-0 last:mb-0 last:pb-0">
                        <div class="flex justify-between gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-800">
                                    {{ $service->name }}
                                </h4>

                                <p class="text-sm text-gray-600">
                                    {{ $service->description }}
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $service->clinic->name ?? 'Brak kliniki' }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="font-semibold text-gray-900">
                                    {{ number_format($service->price, 2) }} zł
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ $service->duration_minutes }} min
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">
                        Brak usług.
                    </p>
                @endforelse
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Umów wizytę
                </h3>

                <p class="text-sm text-gray-500 mb-6">
                    Wybierz usługę, a następnie dostępny termin wizyty.
                </p>

                @if ($doctor->services->count() > 0 && $availabilitySlots->count() > 0)
                    <form method="POST" action="{{ route('appointments.store') }}" id="bookingForm">
                        @csrf

                        <div class="mb-6">
                            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Wybierz usługę
                            </label>

                            <select
                                id="service_id"
                                name="service_id"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
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

                        <div class="mb-4">
                            <div class="flex items-center justify-between gap-4 mb-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900">
                                        Wybierz termin
                                    </h4>

                                    <p class="text-sm text-gray-500">
                                        Tydzień:
                                        {{ $weekStart->format('d.m.Y') }}
                                        -
                                        {{ $weekEnd->format('d.m.Y') }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @if ($week > 0)
                                        <a
                                            href="{{ route('doctors.show', ['doctor' => $doctor, 'week' => $week - 1]) }}"
                                            class="px-3 py-2 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200"
                                        >
                                            ← Poprzedni tydzień
                                        </a>
                                    @endif

                                    @if ($week < 4)
                                        <a
                                            href="{{ route('doctors.show', ['doctor' => $doctor, 'week' => $week + 1]) }}"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200"
                                        >
                                            Następny tydzień →
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4">
                                @foreach ($availabilitySlots as $date => $slots)
                                    @php
                                        $day = \Carbon\Carbon::parse($date);
                                        $dayId = 'day-' . $day->format('Ymd');
                                    @endphp

                                    <div class="border border-gray-200 rounded-xl p-4 day-card">
                                        <div class="text-center border-b border-gray-100 pb-3 mb-3">
                                            <p class="font-semibold text-gray-900">
                                                {{ ucfirst($day->locale('pl')->isoFormat('dddd')) }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ $day->format('d.m') }}
                                            </p>
                                        </div>

                                        <div class="space-y-2">
                                            @foreach ($slots as $index => $slot)
                                                <label
                                                    class="slot-option block cursor-pointer"
                                                    data-clinic-id="{{ $slot->clinic_id }}"
                                                    data-extra="{{ $index >= 4 ? '1' : '0' }}"
                                                    data-day-id="{{ $dayId }}"
                                                    style="{{ $index >= 4 ? 'display: none;' : '' }}"
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
                                                        style="text-align: center; padding: 10px 16px; border-radius: 9999px; background: #dcfce7; color: #064e3b; font-weight: 700; border: 2px solid transparent;"
                                                    >
                                                        {{ $slot->start_time->format('H:i') }}
                                                    </div>

                                                    <p class="text-xs text-gray-400 text-center mt-1">
                                                        {{ $slot->clinic->name ?? 'Brak kliniki' }}
                                                    </p>
                                                </label>
                                            @endforeach
                                        </div>

                                        @if ($slots->count() > 4)
                                            <button
                                                type="button"
                                                class="show-more-slots w-full text-sm text-emerald-700 font-semibold mt-4 hover:text-emerald-900"
                                                data-day-id="{{ $dayId }}"
                                            >
                                                Pokaż więcej godzin
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div style="margin-top: 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
                            <p class="text-sm text-gray-500">
                                Po kliknięciu system wstępnie zablokuje wybrany termin i przeniesie Cię do płatności.
                            </p>

                            <button
                                type="submit"
                                style="padding: 10px 20px; background: #16a34a; color: white; border: none; border-radius: 6px; font-weight: 700; cursor: pointer;"
                            >
                                Zarezerwuj wizytę
                            </button>
                        </div>
                    </form>
                @elseif ($doctor->services->count() === 0)
                    <p class="text-gray-500">
                        Ten lekarz nie ma jeszcze dodanych usług.
                    </p>
                @else
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-gray-500">
                            Brak wolnych terminów w tym tygodniu.
                        </p>

                        <div class="flex gap-2">
                            @if ($week > 0)
                                <a
                                    href="{{ route('doctors.show', ['doctor' => $doctor, 'week' => $week - 1]) }}"
                                    class="px-3 py-2 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200"
                                >
                                    ← Poprzedni tydzień
                                </a>
                            @endif

                            @if ($week < 4)
                                <a
                                    href="{{ route('doctors.show', ['doctor' => $doctor, 'week' => $week + 1]) }}"
                                    class="px-3 py-2 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200"
                                >
                                    Następny tydzień →
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Opinie
                </h3>

                @forelse ($doctor->reviews as $review)
                    <div class="border-b border-gray-100 pb-4 mb-4 last:border-b-0 last:mb-0 last:pb-0">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-gray-800">
                                Ocena: {{ $review->rating }}/5
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $review->created_at->format('d.m.Y') }}
                            </p>
                        </div>

                        @if ($review->comment)
                            <p class="text-gray-600 mt-2">
                                {{ $review->comment }}
                            </p>
                        @endif

                        @if ($review->images->count() > 0)
                            <div class="mt-4 flex flex-wrap gap-3">
                                @foreach ($review->images as $image)
                                    <a href="{{ asset($image->image) }}" target="_blank">
                                        <img
                                            src="{{ asset($image->image) }}"
                                            alt="Zdjęcie dodane do opinii"
                                            class="w-32 h-32 object-cover rounded-lg border border-gray-200 hover:opacity-90"
                                        >
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">
                        Brak opinii.
                    </p>
                @endforelse
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
