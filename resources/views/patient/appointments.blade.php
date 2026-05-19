<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Moje wizyty
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Płatność</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akcje</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($appointments as $appointment)
                                @php
                                    $hasReview = \App\Models\Review::where('appointment_id', $appointment->id)->exists();
                                @endphp

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
                                        @if ($appointment->status === 'pending_payment')
                                            <span class="px-2 py-1 rounded text-xs bg-orange-100 text-orange-700">
                                                Oczekuje na płatność
                                            </span>
                                        @elseif ($appointment->status === 'cancelled')
                                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">
                                                Anulowana
                                            </span>
                                        @elseif ($appointment->status === 'pending')
                                            <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">
                                                Oczekująca
                                            </span>
                                        @elseif ($appointment->status === 'confirmed')
                                            <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">
                                                Potwierdzona
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
                                            @if ($appointment->status === 'pending_payment' && $appointment->payment_status !== 'paid')
                                                <a
                                                    href="{{ route('payments.show', $appointment) }}"
                                                    class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200"
                                                >
                                                    Opłać
                                                </a>
                                            @endif

                                            @if (! in_array($appointment->status, ['cancelled', 'completed']))
                                                <form method="POST" action="{{ route('appointments.cancel', $appointment) }}">
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

                                            @if ($appointment->status === 'completed' && ! $hasReview)
                                                <a
                                                    href="{{ route('reviews.create', $appointment) }}"
                                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200"
                                                >
                                                    Dodaj opinię
                                                </a>
                                            @endif

                                            @if ($appointment->status === 'completed' && $hasReview)
                                                <span class="text-gray-400 text-xs">
                                                    Opinia dodana
                                                </span>
                                            @endif

                                            @if ($appointment->status === 'cancelled')
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
