<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Wszystkie wizyty
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
                    Lista wszystkich wizyt
                </h1>

                <p class="text-gray-600 mt-1">
                    Administrator widzi wszystkie wizyty zapisane w systemie, może je filtrować i zarządzać ich statusem oraz sprawdzać płatności.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Filtry
                </h2>

                <form method="GET" action="{{ route('admin.appointments') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label for="patient" class="block text-sm font-medium text-gray-700 mb-1">
                                Pacjent
                            </label>

                            <input
                                type="text"
                                id="patient"
                                name="patient"
                                value="{{ request('patient') }}"
                                placeholder="np. Piotr"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="doctor" class="block text-sm font-medium text-gray-700 mb-1">
                                Lekarz
                            </label>

                            <input
                                type="text"
                                id="doctor"
                                name="doctor"
                                value="{{ request('doctor') }}"
                                placeholder="np. Jan"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="clinic" class="block text-sm font-medium text-gray-700 mb-1">
                                Klinika / miasto
                            </label>

                            <input
                                type="text"
                                id="clinic"
                                name="clinic"
                                value="{{ request('clinic') }}"
                                placeholder="np. Rzeszów"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                                <option value="">Wszystkie</option>
                                <option value="pending_payment" @selected(request('status') === 'pending_payment')>
                                    Oczekuje na płatność
                                </option>
                                <option value="pending" @selected(request('status') === 'pending')>
                                    Oczekująca
                                </option>
                                <option value="confirmed" @selected(request('status') === 'confirmed')>
                                    Potwierdzona
                                </option>
                                <option value="cancelled" @selected(request('status') === 'cancelled')>
                                    Anulowana
                                </option>
                                <option value="completed" @selected(request('status') === 'completed')>
                                    Zakończona
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">
                                Data wizyty od
                            </label>

                            <input
                                type="date"
                                id="date_from"
                                name="date_from"
                                value="{{ request('date_from') }}"
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
                            href="{{ route('admin.appointments') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            @if ($appointments->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pacjent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lekarz</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usługa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klinika</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Długość</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Płatność</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akcje</th>
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
                                            <br>
                                            <span class="text-xs text-gray-500">
                                                {{ number_format($appointment->payment_amount ?? $appointment->service->price, 2) }} zł
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex flex-wrap gap-2">
                                            @if ($appointment->status === 'pending' && $appointment->payment_status === 'paid')
                                                <form method="POST" action="{{ route('admin.appointments.confirm', $appointment) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200"
                                                    >
                                                        Potwierdź
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($appointment->status === 'confirmed')
                                                <form method="POST" action="{{ route('admin.appointments.complete', $appointment) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200"
                                                    >
                                                        Zakończ
                                                    </button>
                                                </form>
                                            @endif

                                            @if (! in_array($appointment->status, ['cancelled', 'completed']))
                                                <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Czy na pewno chcesz anulować tę wizytę?')"
                                                        class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200"
                                                    >
                                                        Anuluj
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($appointment->status === 'pending_payment')
                                                <span class="text-gray-400 text-xs">
                                                    Czeka na płatność
                                                </span>
                                            @endif

                                            @if (in_array($appointment->status, ['cancelled', 'completed']))
                                                <span class="text-gray-400 text-xs">
                                                    Brak akcji
                                                </span>
                                            @endif
                                        </div>
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
                        Brak wizyt spełniających wybrane filtry.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
