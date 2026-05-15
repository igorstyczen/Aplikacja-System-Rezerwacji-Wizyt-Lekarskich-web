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

            @if ($doctors->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($doctors as $doctor)
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                    @if ($doctor->photo_url)
                                        <img src="{{ $doctor->photo_url }}" alt="Zdjęcie lekarza" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-500 text-xl font-bold">
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

                                <a href="#"
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
