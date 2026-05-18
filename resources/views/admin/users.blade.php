<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista użytkowników
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
                <h1 class="text-2xl font-bold text-gray-900">
                    Użytkownicy systemu
                </h1>

                <p class="text-gray-600 mt-1">
                    Administrator widzi listę kont, może filtrować użytkowników i zmieniać ich role.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Filtry
                </h2>

                <form method="GET" action="{{ route('admin.users') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Imię / nazwa
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
                                placeholder="np. test.pl"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                Rola
                            </label>
                            <select
                                id="role"
                                name="role"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                                <option value="">Wszystkie</option>
                                <option value="admin" @selected(request('role') === 'admin')>admin</option>
                                <option value="doctor" @selected(request('role') === 'doctor')>doctor</option>
                                <option value="patient" @selected(request('role') === 'patient')>patient</option>
                            </select>
                        </div>

                        <div>
                            <label for="created_from" class="block text-sm font-medium text-gray-700 mb-1">
                                Utworzony od
                            </label>
                            <input
                                type="date"
                                id="created_from"
                                name="created_from"
                                value="{{ request('created_from') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
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
                            href="{{ route('admin.users') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            @if ($users->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Imię / nazwa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Aktualna rola
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Zmień rolę
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Data utworzenia
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $user->id }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $user->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($user->role === 'admin')
                                            <span class="px-2 py-1 rounded text-xs bg-purple-100 text-purple-700">
                                                admin
                                            </span>
                                        @elseif ($user->role === 'doctor')
                                            <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">
                                                doctor
                                            </span>
                                        @elseif ($user->role === 'patient')
                                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                                patient
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                                                {{ $user->role }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($user->id === Auth::id())
                                            <span class="text-gray-400 text-xs">
                                                Nie można zmienić własnej roli
                                            </span>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="flex gap-2 items-center">
                                                @csrf
                                                @method('PATCH')

                                                <select name="role" class="border-gray-300 rounded-md text-sm">
                                                    <option value="patient" @selected($user->role === 'patient')>
                                                        patient
                                                    </option>
                                                    <option value="doctor" @selected($user->role === 'doctor')>
                                                        doctor
                                                    </option>
                                                    <option value="admin" @selected($user->role === 'admin')>
                                                        admin
                                                    </option>
                                                </select>

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Czy na pewno chcesz zmienić rolę tego użytkownika?')"
                                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200"
                                                >
                                                    Zapisz
                                                </button>
                                            </form>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $user->created_at->format('d.m.Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-gray-600">
                        Brak użytkowników do wyświetlenia.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
