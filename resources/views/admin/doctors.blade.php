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
                <h1 class="text-2xl font-bold text-gray-900">
                    Lista lekarzy
                </h1>

                <p class="text-gray-600 mt-1">
                    Administrator może zweryfikować lekarza albo cofnąć jego weryfikację.
                </p>
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
                        Brak lekarzy do wyświetlenia.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
