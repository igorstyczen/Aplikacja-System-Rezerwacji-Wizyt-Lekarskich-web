<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edycja terminu
            </h2>

            <a href="{{ route('doctor.schedule') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Wróć do grafiku
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-6">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Edytuj wolny termin
                </h1>

                <p class="text-gray-600 mb-6">
                    Możesz edytować tylko wolne terminy. Terminy zarezerwowane przez pacjentów nie mogą być zmieniane.
                </p>

                <form method="POST" action="{{ route('doctor.schedule.update', $slot) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

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
                            @foreach ($clinics as $clinic)
                                <option value="{{ $clinic->id }}" @selected(old('clinic_id', $slot->clinic_id) == $clinic->id)>
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
                            value="{{ old('date', $slot->start_time->format('Y-m-d')) }}"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                        >
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Godzina od
                            </label>

                            <input
                                type="time"
                                id="start_time"
                                name="start_time"
                                value="{{ old('start_time', $slot->start_time->format('H:i')) }}"
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
                                value="{{ old('end_time', $slot->end_time->format('H:i')) }}"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                        >
                            Zapisz zmiany
                        </button>

                        <a
                            href="{{ route('doctor.schedule') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200"
                        >
                            Anuluj
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
