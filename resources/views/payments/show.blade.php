<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Płatność testowa
            </h2>

            <a href="{{ route('patient.appointments') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Moje wizyty
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

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
                <h1 class="text-2xl font-bold text-gray-900 mb-4">
                    Podsumowanie wizyty
                </h1>

                <div class="space-y-2 text-sm text-gray-700">
                    <p>
                        <strong>Lekarz:</strong>
                        {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                    </p>

                    <p>
                        <strong>Usługa:</strong>
                        {{ $appointment->service->name }}
                    </p>

                    <p>
                        <strong>Klinika:</strong>
                        {{ $appointment->clinic->name }}, {{ $appointment->clinic->city }}
                    </p>

                    <p>
                        <strong>Termin:</strong>
                        {{ $appointment->date->format('d.m.Y H:i') }}
                    </p>

                    <p>
                        <strong>Kwota:</strong>
                        {{ number_format($appointment->payment_amount ?? $appointment->service->price, 2) }} zł
                    </p>

                    <p>
                        <strong>Status płatności:</strong>

                        @if ($appointment->payment_status === 'paid')
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                Opłacona
                            </span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">
                                Nieopłacona
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            @if ($appointment->payment_status === 'paid')
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-green-700 font-semibold">
                        Ta wizyta została już opłacona.
                    </p>

                    <a
                        href="{{ route('patient.appointments') }}"
                        class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                    >
                        Przejdź do moich wizyt
                    </a>
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Wybierz metodę płatności testowej
                    </h2>

                    <form method="POST" action="{{ route('payments.pay', $appointment) }}" id="paymentForm">
                        @csrf

                        <div class="space-y-3 mb-6">
                            <label class="flex items-center gap-3 border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="blik" required>
                                <span>
                                    <strong>BLIK</strong>
                                    <br>
                                    <span class="text-sm text-gray-500">
                                        Wymaga 6-cyfrowego kodu BLIK.
                                    </span>
                                </span>
                            </label>

                            <div id="blikFields" class="hidden border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <label for="blik_code" class="block text-sm font-medium text-gray-700 mb-1">
                                    Kod BLIK
                                </label>

                                <input
                                    type="text"
                                    id="blik_code"
                                    name="blik_code"
                                    maxlength="6"
                                    pattern="[0-9]{6}"
                                    placeholder="np. 123456"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                >

                                <p class="text-xs text-gray-500 mt-1">
                                    Wpisz 6 cyfr.
                                </p>
                            </div>

                            <label class="flex items-center gap-3 border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="card" required>
                                <span>
                                    <strong>Karta bankowa</strong>
                                    <br>
                                    <span class="text-sm text-gray-500">
                                        Wymaga numeru karty, daty ważności i CVV.
                                    </span>
                                </span>
                            </label>

                            <div id="cardFields" class="hidden border border-gray-200 rounded-lg p-4 bg-gray-50 space-y-4">
                                <div>
                                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">
                                        Numer karty
                                    </label>

                                    <input
                                        type="text"
                                        id="card_number"
                                        name="card_number"
                                        maxlength="16"
                                        pattern="[0-9]{16}"
                                        placeholder="np. 4242424242424242"
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                    >

                                    <p class="text-xs text-gray-500 mt-1">
                                        Wpisz dowolne 16 cyfr.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-1">
                                            Data ważności
                                        </label>

                                        <input
                                            type="text"
                                            id="card_expiry"
                                            name="card_expiry"
                                            maxlength="5"
                                            pattern="(0[1-9]|1[0-2])/[0-9]{2}"
                                            placeholder="MM/RR"
                                            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                        >
                                    </div>

                                    <div>
                                        <label for="card_cvv" class="block text-sm font-medium text-gray-700 mb-1">
                                            CVV
                                        </label>

                                        <input
                                            type="text"
                                            id="card_cvv"
                                            name="card_cvv"
                                            maxlength="3"
                                            pattern="[0-9]{3}"
                                            placeholder="np. 123"
                                            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                        >
                                    </div>
                                </div>
                            </div>

                        <button
                            type="submit"
                            style="display: block; width: 100%; padding: 14px 20px; background: #16a34a; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;"
                        >
                            Opłać {{ number_format($appointment->payment_amount ?? $appointment->service->price, 2) }} zł
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const methodInputs = document.querySelectorAll('input[name="payment_method"]');

            const blikFields = document.getElementById('blikFields');
            const cardFields = document.getElementById('cardFields');

            const blikCode = document.getElementById('blik_code');
            const cardNumber = document.getElementById('card_number');
            const cardExpiry = document.getElementById('card_expiry');
            const cardCvv = document.getElementById('card_cvv');

            function resetRequiredFields() {
                blikCode.required = false;
                cardNumber.required = false;
                cardExpiry.required = false;
                cardCvv.required = false;
            }

            function hideAllFields() {
                blikFields.classList.add('hidden');
                cardFields.classList.add('hidden');
            }

            function updatePaymentFields() {
                const selected = document.querySelector('input[name="payment_method"]:checked');

                resetRequiredFields();
                hideAllFields();

                if (!selected) {
                    return;
                }

                if (selected.value === 'blik') {
                    blikFields.classList.remove('hidden');
                    blikCode.required = true;
                }

                if (selected.value === 'card') {
                    cardFields.classList.remove('hidden');
                    cardNumber.required = true;
                    cardExpiry.required = true;
                    cardCvv.required = true;
                }
            }

            methodInputs.forEach(function (input) {
                input.addEventListener('change', updatePaymentFields);
            });

            updatePaymentFields();
        });
    </script>
</x-app-layout>
