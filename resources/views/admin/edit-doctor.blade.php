<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edycja lekarza
            </h2>

            <a href="{{ route('admin.doctors') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Wróć do listy lekarzy
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-6">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $doctor->first_name }} {{ $doctor->last_name }}
                </h1>

                <p class="text-gray-600 mt-1">
                    Email konta: {{ $doctor->user->email ?? 'Brak emaila' }}
                </p>

                <p class="text-gray-600 mt-1">
                    Specjalizacje:
                    @forelse ($doctor->specializations as $specialization)
                        {{ $specialization->specialization_name }}@if (!$loop->last), @endif
                    @empty
                        Brak specjalizacji
                    @endforelse
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Imię
                            </label>

                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                value="{{ old('first_name', $doctor->first_name) }}"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nazwisko
                            </label>

                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                value="{{ old('last_name', $doctor->last_name) }}"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Aktualne zdjęcie lekarza
                        </label>

                        <div style="width: 112px; height: 112px; border-radius: 9999px; overflow: hidden; background: #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if ($doctor->photo_url)
                                <img
                                    src="{{ asset($doctor->photo_url) }}"
                                    alt="Zdjęcie lekarza"
                                    style="width: 112px; height: 112px; object-fit: cover; object-position: center 20%; display: block;"
                                >
                            @else
                                <span class="text-gray-500 text-3xl font-bold">
                                    {{ mb_substr($doctor->first_name, 0, 1) }}{{ mb_substr($doctor->last_name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">
                            Zmień zdjęcie lekarza
                        </label>

                        <input
                            type="file"
                            id="photo"
                            name="photo"
                            accept="image/jpeg,image/png,image/webp"
                            class="w-full border border-gray-300 rounded-md shadow-sm text-sm p-2"
                        >

                        <p class="text-xs text-gray-500 mt-1">
                            Dozwolone formaty: JPG, PNG, WEBP. Maksymalnie 2 MB. Jeśli nie wybierzesz pliku, obecne zdjęcie zostanie bez zmian.
                        </p>
                    </div>

                    <div class="mb-4">
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                            Opis lekarza
                        </label>

                        <textarea
                            id="bio"
                            name="bio"
                            rows="5"
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            placeholder="Opis lekarza widoczny na profilu..."
                        >{{ old('bio', $doctor->bio) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                name="is_for_adults"
                                value="1"
                                @checked(old('is_for_adults', $doctor->is_for_adults))
                                class="rounded border-gray-300"
                            >
                            Przyjmuje dorosłych
                        </label>

                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                name="is_for_children"
                                value="1"
                                @checked(old('is_for_children', $doctor->is_for_children))
                                class="rounded border-gray-300"
                            >
                            Przyjmuje dzieci
                        </label>

                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                name="is_verified"
                                value="1"
                                @checked(old('is_verified', $doctor->is_verified))
                                class="rounded border-gray-300"
                            >
                            Lekarz zweryfikowany
                        </label>
                    </div>

                    <div class="flex gap-3">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                        >
                            Zapisz zmiany
                        </button>

                        <a
                            href="{{ route('admin.doctors') }}"
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
