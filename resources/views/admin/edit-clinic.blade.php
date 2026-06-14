<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edycja kliniki
            </h2>

            <a href="{{ route('admin.clinics') }}" style="font-size: 14px; color: #2563eb; font-weight: 700; text-decoration: none;">
                ← Wróć do listy klinik
            </a>
        </div>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 900px; margin: 0 auto;">

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

            @php
                $selectedDoctors = old('doctors', $assignedDoctors);
            @endphp

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h1 style="font-size: 24px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    {{ $clinic->name }}
                </h1>

                <p style="color: #6b7280; font-size: 14px; margin-bottom: 28px;">
                    Edytuj dane kliniki i przypisanych lekarzy.
                </p>

                <form method="POST" action="{{ route('admin.clinics.update', $clinic) }}" style="display: flex; flex-direction: column; gap: 22px;">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label for="name" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Nazwa kliniki
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $clinic->name) }}"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="city" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Miasto
                            </label>

                            <input
                                type="text"
                                id="city"
                                name="city"
                                value="{{ old('city', $clinic->city) }}"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="address" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Adres
                            </label>

                            <input
                                type="text"
                                id="address"
                                name="address"
                                value="{{ old('address', $clinic->address) }}"
                                required
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="details" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                            Szczegóły
                        </label>

                        <textarea
                            id="details"
                            name="details"
                            rows="4"
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 14px; font-size: 14px; resize: vertical;"
                        >{{ old('details', $clinic->details) }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 800; color: #374151; margin-bottom: 12px;">
                            Przypisani lekarze
                        </label>

                        @if ($doctors->count() > 0)
                            <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px;">
                                @foreach ($doctors as $doctor)
                                    <label style="display: flex; align-items: center; gap: 10px; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px 14px; background: #f9fafb; cursor: pointer;">
                                        <input
                                            type="checkbox"
                                            name="doctors[]"
                                            value="{{ $doctor->id }}"
                                            @checked(in_array($doctor->id, $selectedDoctors))
                                        >

                                        <span style="font-size: 14px; font-weight: 700; color: #374151;">
                                            Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p style="font-size: 14px; color: #6b7280;">
                                Brak lekarzy w systemie.
                            </p>
                        @endif
                    </div>

                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 26px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer;"
                        >
                            Zapisz zmiany
                        </button>

                        <a
                            href="{{ route('admin.clinics') }}"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 12px; text-decoration: none;"
                        >
                            Anuluj
                        </a>
                    </div>
                </form>

                @if ($canDelete)
                    <form method="POST" action="{{ route('admin.clinics.delete', $clinic) }}" style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #fecaca;">
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            onclick="return confirm('Czy na pewno chcesz usunąć tę klinikę?')"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 20px; background: #fee2e2; color: #b91c1c; font-size: 13px; font-weight: 900; border-radius: 10px; border: none; cursor: pointer;"
                        >
                            Usuń klinikę
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
