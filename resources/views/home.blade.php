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
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Platforma rezerwacji wizyt lekarskich
                </h1>
                <p class="text-gray-600">
                    Wybierz lekarza, sprawdź dostępne terminy i umów wizytę.
                </p>
            </div>

            <div class="mb-6 bg-white p-6 rounded-lg shadow-sm">
    <form method="GET" action="{{ route('home') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
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
                <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">
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
                <label for="tag" class="block text-sm font-medium text-gray-700 mb-1">
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
                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
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

            <div class="flex items-end">
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        name="for_children"
                        value="1"
                        @checked(request()->boolean('for_children'))
                        class="rounded border-gray-300 text-blue-600 shadow-sm"
                    >
                    <span class="ms-2 text-sm text-gray-700">
                        Przyjmuje dzieci
                    </span>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
            >
                Filtruj
            </button>

            <a
                href="{{ route('home') }}"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200"
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

                                <a href="{{ route('doctors.show', $doctor) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
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
