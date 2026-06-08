<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Płatność
            </h2>

            <a href="{{ route('patient.appointments') }}" style="font-size: 14px; color: #2563eb; font-weight: 700; text-decoration: none;">
                ← Moje wizyty
            </a>
        </div>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 980px; margin: 0 auto;">

            @if (session('success'))
                <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 0 0 6px 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #16a34a; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                    Płatność za wizytę
                </p>

                <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                    Podsumowanie rezerwacji
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 760px;">
                    Sprawdź dane wizyty i wybierz metodę płatności. Po opłaceniu wizyta zostanie oznaczona jako opłacona.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: minmax(0, 1fr) 360px; gap: 28px; align-items: start;">

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Dane wizyty
                    </h2>

                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
                        Szczegóły wizyty, za którą wykonywana jest płatność.
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        <div style="padding: 18px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 16px;">
                            <p style="font-size: 13px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">
                                Lekarz
                            </p>

                            <p style="font-size: 16px; font-weight: 900; color: #111827;">
                                Dr {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                            </p>
                        </div>

                        <div style="padding: 18px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 16px;">
                            <p style="font-size: 13px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">
                                Usługa
                            </p>

                            <p style="font-size: 16px; font-weight: 900; color: #111827;">
                                {{ $appointment->service->name }}
                            </p>

                            <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                                Czas trwania: {{ $appointment->length }} min
                            </p>
                        </div>

                        <div style="padding: 18px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 16px;">
                            <p style="font-size: 13px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">
                                Klinika
                            </p>

                            <p style="font-size: 16px; font-weight: 900; color: #111827;">
                                {{ $appointment->clinic->name }}
                            </p>

                            <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                                {{ $appointment->clinic->city }}
                            </p>
                        </div>

                        <div style="padding: 18px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 16px;">
                            <p style="font-size: 13px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">
                                Termin
                            </p>

                            <p style="font-size: 16px; font-weight: 900; color: #111827;">
                                {{ $appointment->date->format('d.m.Y') }}
                            </p>

                            <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                                Godzina: {{ $appointment->date->format('H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 28px;">
                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 18px;">
                            Kwota do zapłaty
                        </h2>

                        <p style="font-size: 36px; font-weight: 900; color: #111827; margin-bottom: 14px;">
                            {{ number_format($appointment->payment_amount ?? $appointment->service->price, 2) }} zł
                        </p>

                        <div style="margin-top: 16px;">
                            <p style="font-size: 13px; font-weight: 900; color: #6b7280; margin-bottom: 8px;">
                                Status płatności
                            </p>

                            @if ($appointment->payment_status === 'paid')
                                <span style="display: inline-flex; align-items: center; padding: 7px 12px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                    Opłacona
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; padding: 7px 12px; background: #fee2e2; color: #991b1b; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                    Nieopłacona
                                </span>
                            @endif
                        </div>
                    </div>

                    @if ($appointment->payment_status === 'paid')
                        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                            <h2 style="font-size: 22px; font-weight: 900; color: #166534; margin-bottom: 10px;">
                                Wizyta została już opłacona
                            </h2>

                            <p style="font-size: 14px; color: #4b5563; line-height: 1.7; margin-bottom: 22px;">
                                Ta rezerwacja ma już status opłaconej. Możesz wrócić do listy swoich wizyt.
                            </p>

                            <a
                                href="{{ route('patient.appointments') }}"
                                style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 12px 20px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; text-decoration: none;"
                            >
                                Przejdź do moich wizyt
                            </a>
                        </div>
                    @else
                        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                            <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                                Wybierz metodę płatności
                            </h2>

                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-bottom: 24px;">
                                To płatność testowa w aplikacji. Dane są walidowane tylko na potrzeby projektu.
                            </p>

                            <form method="POST" action="{{ route('payments.pay', $appointment) }}" id="paymentForm">
                                @csrf

                                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
                                    <label style="display: flex; align-items: flex-start; gap: 14px; border: 1px solid #e5e7eb; border-radius: 16px; padding: 18px; cursor: pointer; background: #f9fafb;">
                                        <input type="radio" name="payment_method" value="blik" required style="margin-top: 4px;">

                                        <span>
                                            <strong style="font-size: 15px; color: #111827;">
                                                BLIK
                                            </strong>
                                            <br>
                                            <span style="font-size: 13px; color: #6b7280;">
                                                Wymaga 6-cyfrowego kodu BLIK.
                                            </span>
                                        </span>
                                    </label>

                                    <div id="blikFields" style="display: none; border: 1px solid #e5e7eb; border-radius: 16px; padding: 18px; background: #f9fafb;">
                                        <label for="blik_code" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                            Kod BLIK
                                        </label>

                                        <input
                                            type="text"
                                            id="blik_code"
                                            name="blik_code"
                                            maxlength="6"
                                            pattern="[0-9]{6}"
                                            placeholder="np. 123456"
                                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                        >

                                        <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                                            Wpisz 6 cyfr.
                                        </p>
                                    </div>

                                    <label style="display: flex; align-items: flex-start; gap: 14px; border: 1px solid #e5e7eb; border-radius: 16px; padding: 18px; cursor: pointer; background: #f9fafb;">
                                        <input type="radio" name="payment_method" value="card" required style="margin-top: 4px;">

                                        <span>
                                            <strong style="font-size: 15px; color: #111827;">
                                                Karta bankowa
                                            </strong>
                                            <br>
                                            <span style="font-size: 13px; color: #6b7280;">
                                                Wymaga numeru karty, daty ważności i CVV.
                                            </span>
                                        </span>
                                    </label>

                                    <div id="cardFields" style="display: none; border: 1px solid #e5e7eb; border-radius: 16px; padding: 18px; background: #f9fafb;">
                                        <div style="margin-bottom: 18px;">
                                            <label for="card_number" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                                Numer karty
                                            </label>

                                            <input
                                                type="text"
                                                id="card_number"
                                                name="card_number"
                                                maxlength="16"
                                                pattern="[0-9]{16}"
                                                placeholder="np. 4242424242424242"
                                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                            >

                                            <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                                                Wpisz 16 cyfr.
                                            </p>
                                        </div>

                                        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
                                            <div>
                                                <label for="card_expiry" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                                    Data ważności
                                                </label>

                                                <input
                                                    type="text"
                                                    id="card_expiry"
                                                    name="card_expiry"
                                                    maxlength="5"
                                                    pattern="(0[1-9]|1[0-2])/[0-9]{2}"
                                                    placeholder="MM/RR"
                                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                                >
                                            </div>

                                            <div>
                                                <label for="card_cvv" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                                    CVV
                                                </label>

                                                <input
                                                    type="text"
                                                    id="card_cvv"
                                                    name="card_cvv"
                                                    maxlength="3"
                                                    pattern="[0-9]{3}"
                                                    placeholder="np. 123"
                                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 14px 24px; background: #16a34a; color: white; border: none; border-radius: 12px; font-size: 15px; font-weight: 900; cursor: pointer; box-shadow: 0 10px 20px rgba(22, 163, 74, 0.22);"
                                >
                                    Opłać {{ number_format($appointment->payment_amount ?? $appointment->service->price, 2) }} zł
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

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
                if (blikCode) blikCode.required = false;
                if (cardNumber) cardNumber.required = false;
                if (cardExpiry) cardExpiry.required = false;
                if (cardCvv) cardCvv.required = false;
            }

            function hideAllFields() {
                if (blikFields) blikFields.style.display = 'none';
                if (cardFields) cardFields.style.display = 'none';
            }

            function updatePaymentFields() {
                const selected = document.querySelector('input[name="payment_method"]:checked');

                resetRequiredFields();
                hideAllFields();

                if (!selected) {
                    return;
                }

                if (selected.value === 'blik') {
                    blikFields.style.display = 'block';
                    blikCode.required = true;
                }

                if (selected.value === 'card') {
                    cardFields.style.display = 'block';
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
