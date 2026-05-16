<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Moje wizyty
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($message)
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded mb-6">
                    {{ $message }}
                </div>
            @endif

            @if ($appointments->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lekarz</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usługa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klinika</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($appointments as $appointment)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $appointment->date->format('d.m.Y H:i') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $appointment->service->name }}
                                        <br>
                                        <span class="text-gray-500">{{ $appointment->length }} min</span>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $appointment->clinic->name }}
                                        <br>
                                        <span class="text-gray-500">{{ $appointment->clinic->city }}</span>
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">
                                            {{ $appointment->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-gray-600">
                        Nie masz jeszcze żadnych wizyt.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
