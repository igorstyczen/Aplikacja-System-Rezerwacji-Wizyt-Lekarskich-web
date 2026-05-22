<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mój grafik lekarza
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-6">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if ($message)
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded mb-6">
                    {{ $message }}
                </div>
            @endif

            @if ($doctor)
                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </h1>

                    <p class="text-gray-600 mt-1">
                        Tutaj możesz dodawać wolne terminy przyjęć oraz zarządzać swoim grafikiem.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Dodaj wolne terminy
                    </h2>

                    @if ($clinics->count() > 0)
                        <form method="POST" action="{{ route('doctor.schedule.store') }}" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                <div>
                                    <label for="clinic_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Klinika
                                    </label>

                                    <select
                                        id="clinic_id"
                                        name="clinic_id"
                                        required
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm"
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
                                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                                        Data
                                    </label>

                                    <input
                                        type="date"
                                        id="date"
                                        name="date"
                                        value="{{ old('date') }}"
                                        required
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                    >
                                </div>

                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                                        Godzina od
                                    </label>

                                    <input
                                        type="time"
                                        id="start_time"
                                        name="start_time"
                                        value="{{ old('start_time') }}"
                                        required
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                    >
                                </div>

                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                                        Godzina do
                                    </label>

                                    <input
                                        type="time"
                                        id="end_time"
                                        name="end_time"
                                        value="{{ old('end_time') }}"
                                        required
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                    >
                                </div>

                                <div>
                                    <label for="slot_duration" class="block text-sm font-medium text-gray-700 mb-1">
                                        Długość wizyty
                                    </label>

                                    <select
                                        id="slot_duration"
                                        name="slot_duration"
                                        required
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                    >
                                        <option value="15" @selected(old('slot_duration') == 15)>15 min</option>
                                        <option value="20" @selected(old('slot_duration') == 20)>20 min</option>
                                        <option value="30" @selected(old('slot_duration', 30) == 30)>30 min</option>
                                        <option value="45" @selected(old('slot_duration') == 45)>45 min</option>
                                        <option value="60" @selected(old('slot_duration') == 60)>60 min</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <input
                                    type="checkbox"
                                    id="repeat_weekly"
                                    name="repeat_weekly"
                                    value="1"
                                    class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                    @checked(old('repeat_weekly'))
                                >

                                <div>
                                    <label for="repeat_weekly" class="text-sm font-medium text-gray-700">
                                        Powtarzaj co tydzień przez miesiąc
                                    </label>

                                    <p class="text-xs text-gray-500 mt-1">
                                        System utworzy ten sam zakres godzin w wybranym dniu tygodnia przez kolejne 4 tygodnie.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-blue-50 border border-blue-100 rounded p-4 text-sm text-blue-800">
                                Przykład: jeśli wybierzesz 10:00–12:00 i długość 30 minut, system utworzy terminy:
                                10:00, 10:30, 11:00 i 11:30.
                                <br>
                                Jeśli zaznaczysz powtarzanie, ten sam zakres zostanie dodany również w kolejne tygodnie.
                                Możesz dodać kilka zakresów jednego dnia, np. 10:00–14:00 w jednej klinice i 15:00–18:00 w drugiej.
                            </div>

                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                            >
                                Dodaj terminy
                            </button>
                        </form>
                    @else
                        <p class="text-gray-600">
                            Nie masz przypisanej żadnej kliniki. Najpierw administrator musi przypisać klinikę do Twojego profilu.
                        </p>
                    @endif
                </div>
            @endif

            @if ($slots->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Lista terminów
                        </h2>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Data
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Godzina
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Klinika
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Akcje
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($slots as $slot)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $slot->start_time->format('d.m.Y') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $slot->start_time->format('H:i') }}
                                        -
                                        {{ $slot->end_time->format('H:i') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $slot->clinic->name ?? 'Brak kliniki' }}
                                        <br>
                                        <span class="text-gray-500">
                                            {{ $slot->clinic->city ?? '' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($slot->status === 'available')
                                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                                Wolny
                                            </span>
                                        @elseif ($slot->status === 'booked')
                                            <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">
                                                Zarezerwowany
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                                                Niedostępny
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($slot->status === 'available')
                                            <div class="flex flex-wrap gap-2">
                                                <a
                                                    href="{{ route('doctor.schedule.edit', $slot) }}"
                                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200"
                                                >
                                                    Edytuj
                                                </a>

                                                <form method="POST" action="{{ route('doctor.schedule.destroy', $slot) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Czy na pewno chcesz usunąć ten wolny termin?')"
                                                        class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200"
                                                    >
                                                        Usuń
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs">
                                                Brak akcji
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-gray-600">
                        Brak terminów w grafiku.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
