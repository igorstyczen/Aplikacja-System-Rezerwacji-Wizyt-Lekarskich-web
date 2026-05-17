<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil lekarza
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

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
                    <div class="flex items-center gap-6">
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

                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                            </h1>

                            <p class="text-gray-600 mt-1">
                                Tutaj możesz zmienić swoje zdjęcie profilowe widoczne dla pacjentów.
                            </p>

                            @if ($doctor->photo_url)
                                <p class="text-xs text-gray-500 mt-2">
                                    Aktualna ścieżka: {{ $doctor->photo_url }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Zmień zdjęcie profilowe
                    </h2>

                    <form method="POST" action="{{ route('doctor.profile.photo') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">
                                Zdjęcie lekarza
                            </label>

                            <input
                                type="file"
                                id="photo"
                                name="photo"
                                accept="image/jpeg,image/png,image/webp"
                                required
                                class="w-full border border-gray-300 rounded-md shadow-sm text-sm p-2"
                            >

                            <p class="text-xs text-gray-500 mt-1">
                                Dozwolone formaty: JPG, PNG, WEBP. Maksymalnie 2 MB.
                            </p>
                        </div>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                        >
                            Zapisz zdjęcie
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
