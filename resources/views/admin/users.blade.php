<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista użytkowników
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Użytkownicy systemu
                </h1>

                <p class="text-gray-600 mt-1">
                    Tutaj administrator widzi listę kont zarejestrowanych w systemie.
                </p>
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
                                    Rola
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
