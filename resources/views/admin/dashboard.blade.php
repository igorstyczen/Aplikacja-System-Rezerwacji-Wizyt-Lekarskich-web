<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel administratora
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Panel administratora
                </h1>

                <p class="text-gray-600 mb-4">
                    Szybki dostęp do najważniejszych sekcji administracyjnych.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a
                        href="{{ route('admin.users') }}"
                        class="block p-4 bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100"
                    >
                        <p class="font-semibold text-blue-900">
                            Użytkownicy
                        </p>
                        <p class="text-sm text-blue-700 mt-1">
                            Zarządzanie kontami i rolami.
                        </p>
                    </a>

                    <a
                        href="{{ route('admin.doctors') }}"
                        class="block p-4 bg-green-50 border border-green-100 rounded-lg hover:bg-green-100"
                    >
                        <p class="font-semibold text-green-900">
                            Lekarze
                        </p>
                        <p class="text-sm text-green-700 mt-1">
                            Profile, weryfikacja i aktywność.
                        </p>
                    </a>

                    <a
                        href="{{ route('admin.appointments') }}"
                        class="block p-4 bg-purple-50 border border-purple-100 rounded-lg hover:bg-purple-100"
                    >
                        <p class="font-semibold text-purple-900">
                            Wizyty
                        </p>
                        <p class="text-sm text-purple-700 mt-1">
                            Statusy wizyt i płatności.
                        </p>
                    </a>

                    <a
                        href="{{ route('admin.specializations') }}"
                        class="block p-4 bg-orange-50 border border-orange-100 rounded-lg hover:bg-orange-100"
                    >
                        <p class="font-semibold text-orange-900">
                            Specjalizacje
                        </p>
                        <p class="text-sm text-orange-700 mt-1">
                            Słownik specjalizacji lekarzy.
                        </p>
                    </a>

                    <a
                        href="{{ route('admin.help-tags') }}"
                        class="block p-4 bg-pink-50 border border-pink-100 rounded-lg hover:bg-pink-100"
                    >
                        <p class="font-semibold text-pink-900">
                            Tagi pomocy
                        </p>
                        <p class="text-sm text-pink-700 mt-1">
                            Obszary problemów pacjentów.
                        </p>
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Użytkownicy</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $usersCount }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Lekarze</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $doctorsCount }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Pacjenci</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $patientsCount }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Wizyty</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $appointmentsCount }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                <div class="bg-orange-50 p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-orange-700">Oczekuje na płatność</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $pendingPaymentAppointmentsCount }}</p>
                </div>

                <div class="bg-blue-50 p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-blue-700">Oczekujące</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $pendingAppointmentsCount }}</p>
                </div>

                <div class="bg-yellow-50 p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-yellow-700">Potwierdzone</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $confirmedAppointmentsCount }}</p>
                </div>

                <div class="bg-red-50 p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-red-700">Anulowane</p>
                    <p class="text-2xl font-bold text-red-900">{{ $cancelledAppointmentsCount }}</p>
                </div>

                <div class="bg-green-50 p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-green-700">Zakończone</p>
                    <p class="text-2xl font-bold text-green-900">{{ $completedAppointmentsCount }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Ostatnie wizyty
                    </h3>
                </div>

                @if ($latestAppointments->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data wizyty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pacjent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lekarz</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usługa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klinika</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Płatność</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($latestAppointments as $appointment)
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
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($appointment->status === 'pending_payment')
                                            <span class="px-2 py-1 rounded text-xs bg-orange-100 text-orange-700">
                                                Oczekuje na płatność
                                            </span>
                                        @elseif ($appointment->status === 'pending')
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

                                    <td class="px-6 py-4 text-sm">
                                        @if ($appointment->payment_status === 'paid')
                                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                                Opłacona
                                            </span>

                                            @if ($appointment->payment_method)
                                                <br>
                                                <span class="text-xs text-gray-500">
                                                    @if ($appointment->payment_method === 'blik')
                                                        BLIK
                                                    @elseif ($appointment->payment_method === 'card')
                                                        Karta bankowa
                                                    @else
                                                        {{ $appointment->payment_method }}
                                                    @endif
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">
                                                Nieopłacona
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6">
                        <p class="text-gray-600">
                            Brak wizyt do wyświetlenia.
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
