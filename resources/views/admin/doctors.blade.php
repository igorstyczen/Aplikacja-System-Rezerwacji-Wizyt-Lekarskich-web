<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lekarze
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Lista lekarzy
                        </h1>

                        <p class="text-gray-600 mt-1">
                            Administrator może dodawać lekarzy, filtrować ich, edytować profile oraz zarządzać weryfikacją.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.doctors.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                    >
                        Dodaj lekarza
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Filtry
                </h2>

                <form method="GET" action="{{ route('admin.doctors') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Imię / nazwisko
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ request('name') }}"
                                placeholder="np. Jan"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>

                            <input
                                type="text"
                                id="email"
                                name="email"
                                value="{{ request('email') }}"
                                placeholder="np. doktor@test.pl"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">
                                Specjalizacja
                            </label>

                            <input
                                type="text"
                                id="specialization"
                                name="specialization"
                                value="{{ request('specialization') }}"
                                placeholder="np. Dermatolog"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="is_verified" class="block text-sm font-medium text-gray-700 mb-1">
                                Status weryfikacji
                            </label>

                            <select
                                id="is_verified"
                                name="is_verified"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                                <option value="">Wszystkie</option>
                                <option value="1" @selected(request('is_verified') === '1')>
                                    Zweryfikowany
                                </option>
                                <option value="0" @selected(request('is_verified') === '0')>
                                    Niezweryfikowany
                                </option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 18px; margin-top: 18px;">
                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 22px; background: #2563eb; color: white; font-size: 14px; font-weight: 800; border-radius: 10px; border: none; cursor: pointer;"
                        >
                            Filtruj
                        </button>

                        <a
                            href="{{ route('admin.doctors') }}"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 22px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 700; border-radius: 10px; text-decoration: none;"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            @if ($doctors->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Lekarz
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Specjalizacje
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
                            @foreach ($doctors as $doctor)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $doctor->id }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $doctor->first_name }} {{ $doctor->last_name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $doctor->user->email ?? 'Brak emaila' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @forelse ($doctor->specializations as $specialization)
                                            {{ $specialization->specialization_name }}@if (!$loop->last), @endif
                                        @empty
                                            Brak specjalizacji
                                        @endforelse
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($doctor->is_verified)
                                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                                Zweryfikowany
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">
                                                Niezweryfikowany
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex flex-wrap gap-2">
                                            <a
                                                href="{{ route('admin.doctors.edit', $doctor) }}"
                                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200"
                                            >
                                                Edytuj
                                            </a>

                                            <form method="POST" action="{{ route('admin.doctors.toggle-verification', $doctor) }}">
                                                @csrf
                                                @method('PATCH')

                                                @if ($doctor->is_verified)
                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Czy na pewno chcesz cofnąć weryfikację tego lekarza?')"
                                                        class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200"
                                                    >
                                                        Cofnij weryfikację
                                                    </button>
                                                @else
                                                    <button
                                                        type="submit"
                                                        class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200"
                                                    >
                                                        Zweryfikuj
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $doctors->links() }}
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-gray-600">
                        Brak lekarzy spełniających wybrane filtry.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
