<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mój grafik lekarza
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($message)
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded mb-6">
                    {{ $message }}
                </div>
            @endif

            @if ($doctor)
                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </h1>

                    <p class="text-gray-600 mt-1">
                        Tutaj widzisz swoje terminy przyjęć oraz ich aktualny status.
                    </p>
                </div>
            @endif

            @if ($slots->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Data
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Godzina
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Klinika
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($slots as $slot)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $slot->start_time->format('d.m.Y') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $slot->start_time->format('H:i') }}
                                        -
                                        {{ $slot->end_time->format('H:i') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $slot->clinic->name ?? 'Brak kliniki' }}
                                        <br>
                                        <span class="text-gray-500">
                                            {{ $slot->clinic->city ?? '' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($slot->status === 'available')
                                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                                Wolny
                                            </span>
                                        @elseif ($slot->status === 'booked')
                                            <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">
                                                Zarezerwowany
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                                                Niedostępny
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-gray-600">
                        Brak terminów w grafiku.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
