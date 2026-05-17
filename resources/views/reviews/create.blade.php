<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dodaj opinię
        </h2>
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

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Oceń wizytę
                </h1>

                <div class="mt-4 text-gray-700 space-y-1">
                    <p>
                        <strong>Lekarz:</strong>
                        {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                    </p>

                    <p>
                        <strong>Usługa:</strong>
                        {{ $appointment->service->name }}
                    </p>

                    <p>
                        <strong>Klinika:</strong>
                        {{ $appointment->clinic->name }}
                    </p>

                    <p>
                        <strong>Data:</strong>
                        {{ $appointment->date->format('d.m.Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <form method="POST" action="{{ route('reviews.store', $appointment) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">
                            Ocena
                        </label>

                        <select
                            id="rating"
                            name="rating"
                            class="w-full border-gray-300 rounded-md shadow-sm"
                            required
                        >
                            <option value="">Wybierz ocenę</option>
                            <option value="5" @selected(old('rating') == 5)>5 — bardzo dobrze</option>
                            <option value="4" @selected(old('rating') == 4)>4 — dobrze</option>
                            <option value="3" @selected(old('rating') == 3)>3 — średnio</option>
                            <option value="2" @selected(old('rating') == 2)>2 — słabo</option>
                            <option value="1" @selected(old('rating') == 1)>1 — bardzo słabo</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">
                            Komentarz
                        </label>

                        <textarea
                            id="comment"
                            name="comment"
                            rows="5"
                            class="w-full border-gray-300 rounded-md shadow-sm"
                            placeholder="Opisz swoją wizytę..."
                        >{{ old('comment') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">
                            Zdjęcie do opinii
                        </label>

                        <input
                            type="file"
                            id="photo"
                            name="photo"
                            accept="image/jpeg,image/png,image/webp"
                            class="w-full border border-gray-300 rounded-md shadow-sm text-sm p-2"
                        >

                        <p class="text-xs text-gray-500 mt-1">
                            Dozwolone formaty: JPG, PNG, WEBP. Maksymalnie 2 MB.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                        >
                            Dodaj opinię
                        </button>

                        <a
                            href="{{ route('patient.appointments') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200"
                        >
                            Wróć
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
