<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Wszystkie wizyty
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Lista wszystkich wizyt
                </h1>

                <p class="text-gray-600 mt-1">
                    Administrator widzi wszystkie wizyty zapisane w systemie.
                </p>
            </div>

            @if ($appointments->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Data
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Pacjent
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Lekarz
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Usługa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Klinika
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Długość
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($appointments as $appointment)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $appointment->date->format('d.m.Y H:i') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $appointment->service->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $appointment->clinic->name }}
                                        <br>
                                        <span class="text-gray-500">
                                            {{ $appointment->clinic->city }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $appointment->length }} min
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($appointment->status === 'pending')
                                            <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">
                                                Oczekująca
                                            </span>
                                        @elseif ($appointment->status === 'confirmed')
                                            <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">
                                                Potwierdzona
                                            </span>
                                        @elseif ($appointment->status === 'cancelled')
                                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">
                                                Anulowana
                                            </span>
                                        @elseif ($appointment->status === 'completed')
                                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                                Zakończona
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                                                {{ $appointment->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $appointments->links() }}
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-gray-600">
                        Brak wizyt do wyświetlenia.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
