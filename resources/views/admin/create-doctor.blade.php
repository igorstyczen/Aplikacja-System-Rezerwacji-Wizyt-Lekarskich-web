<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dodaj lekarza
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
                    Nowy lekarz
                </h1>

                <p class="text-gray-600 mt-1">
                    Formularz tworzy jednocześnie konto użytkownika z rolą doctor oraz profil lekarza.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <form method="POST" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Dane konta użytkownika
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nazwa użytkownika
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="np. Jan Kowalski"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="np. doktor3@test.pl"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Numer telefonu
                            </label>

                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="np. 123456789"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Hasło
                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                placeholder="minimum 8 znaków"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Dane profilu lekarza
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Imię lekarza
                            </label>

                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                required
                                placeholder="np. Jan"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nazwisko lekarza
                            </label>

                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                required
                                placeholder="np. Kowalski"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>
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
                        >{{ old('bio') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">
                            Zdjęcie lekarza
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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                name="is_for_adults"
                                value="1"
                                @checked(old('is_for_adults', true))
                                class="rounded border-gray-300"
                            >
                            Przyjmuje dorosłych
                        </label>

                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                name="is_for_children"
                                value="1"
                                @checked(old('is_for_children'))
                                class="rounded border-gray-300"
                            >
                            Przyjmuje dzieci
                        </label>

                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                name="is_verified"
                                value="1"
                                @checked(old('is_verified'))
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
                            Dodaj lekarza
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
