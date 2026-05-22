<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kliniki
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

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Zarządzanie klinikami
                </h1>

                <p class="text-gray-600">
                    Administrator dodaje kliniki i przypisuje do nich wielu lekarzy.
                    Lekarz później wybiera przypisane kliniki przy tworzeniu usług oraz grafiku.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Dodaj klinikę
                </h2>

                <form method="POST" action="{{ route('admin.clinics.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nazwa kliniki
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="np. Klinika Ortopedyczna"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Miasto
                            </label>

                            <input
                                type="text"
                                name="city"
                                value="{{ old('city') }}"
                                placeholder="np. Kraków"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Adres
                            </label>

                            <input
                                type="text"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="np. Jana Dekerta 18/2"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Szczegóły
                        </label>

                        <textarea
                            name="details"
                            rows="3"
                            placeholder="Dodatkowe informacje o klinice."
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                        >{{ old('details') }}</textarea>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                            Przypisz lekarzy do kliniki
                        </h3>

                        @if ($doctors->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                @foreach ($doctors as $doctor)
                                    <label class="flex items-center gap-2 border border-gray-200 rounded p-2">
                                        <input
                                            type="checkbox"
                                            name="doctors[]"
                                            value="{{ $doctor->id }}"
                                            @checked(in_array($doctor->id, old('doctors', [])))
                                            class="rounded border-gray-300"
                                        >

                                        <span class="text-sm text-gray-700">
                                            Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">
                                Brak lekarzy w systemie.
                            </p>
                        @endif
                    </div>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                    >
                        Dodaj klinikę
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Filtry
                </h2>

                <form method="GET" action="{{ route('admin.clinics') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input
                        type="text"
                        name="name"
                        value="{{ request('name') }}"
                        placeholder="Nazwa kliniki"
                        class="border-gray-300 rounded-md shadow-sm text-sm"
                    >

                    <input
                        type="text"
                        name="city"
                        value="{{ request('city') }}"
                        placeholder="Miasto"
                        class="border-gray-300 rounded-md shadow-sm text-sm"
                    >

                    <input
                        type="text"
                        name="doctor"
                        value="{{ request('doctor') }}"
                        placeholder="Lekarz"
                        class="border-gray-300 rounded-md shadow-sm text-sm"
                    >

                    <div class="flex gap-2">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                        >
                            Filtruj
                        </button>

                        <a
                            href="{{ route('admin.clinics') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                @if ($clinics->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach ($clinics as $clinic)
                            @php
                                $assignedDoctors = $clinic->doctors->pluck('id')->toArray();
                            @endphp

                            <div class="p-6">
                                <form method="POST" action="{{ route('admin.clinics.update', $clinic) }}" class="space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Nazwa kliniki
                                            </label>

                                            <input
                                                type="text"
                                                name="name"
                                                value="{{ $clinic->name }}"
                                                required
                                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Miasto
                                            </label>

                                            <input
                                                type="text"
                                                name="city"
                                                value="{{ $clinic->city }}"
                                                required
                                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Adres
                                            </label>

                                            <input
                                                type="text"
                                                name="address"
                                                value="{{ $clinic->address }}"
                                                required
                                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                            >
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Szczegóły
                                        </label>

                                        <textarea
                                            name="details"
                                            rows="3"
                                            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                        >{{ $clinic->details }}</textarea>
                                    </div>

                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                                            Przypisani lekarze
                                        </h3>

                                        @if ($doctors->count() > 0)
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                                @foreach ($doctors as $doctor)
                                                    <label class="flex items-center gap-2 border border-gray-200 rounded p-2">
                                                        <input
                                                            type="checkbox"
                                                            name="doctors[]"
                                                            value="{{ $doctor->id }}"
                                                            @checked(in_array($doctor->id, $assignedDoctors))
                                                            class="rounded border-gray-300"
                                                        >

                                                        <span class="text-sm text-gray-700">
                                                            Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500">
                                                Brak lekarzy w systemie.
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap gap-3 items-center">
                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                                        >
                                            Zapisz zmiany
                                        </button>
                                </form>

                                        @if (
                                            ! $clinic->services()->exists()
                                            && ! $clinic->availabilitySlots()->exists()
                                            && ! $clinic->appointments()->exists()
                                        )
                                            <form method="POST" action="{{ route('admin.clinics.delete', $clinic) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Czy na pewno chcesz usunąć tę klinikę?')"
                                                    class="px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-md hover:bg-red-200"
                                                >
                                                    Usuń
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-sm text-gray-400">
                                                Nie można usunąć — klinika jest używana w systemie.
                                            </span>
                                        @endif
                                    </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6">
                        <p class="text-gray-600">
                            Brak klinik do wyświetlenia.
                        </p>
                    </div>
                @endif
            </div>

            <div class="mt-6">
                {{ $clinics->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
