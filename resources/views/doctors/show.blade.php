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

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start gap-6">
                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                        @if ($doctor->photo_url)
                            <img src="{{ $doctor->photo_url }}" alt="Zdjęcie lekarza" class="w-full h-full object-cover">
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

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
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Wolne terminy
                </h3>

                @php
                    $availableSlots = $doctor->availabilitySlots->where('status', 'available');
                @endphp

                @if ($availableSlots->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($availableSlots as $slot)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="font-semibold text-gray-900">
                                    {{ $slot->start_time->format('d.m.Y') }}
                                </p>
                                <p class="text-gray-600">
                                    {{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $slot->clinic->name ?? 'Brak kliniki' }}
                                </p>

                                <button class="mt-3 w-full px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                                    Umów wizytę
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">
                        Brak wolnych terminów.
                    </p>
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
                    </div>
                @empty
                    <p class="text-gray-500">
                        Brak opinii.
                    </p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
