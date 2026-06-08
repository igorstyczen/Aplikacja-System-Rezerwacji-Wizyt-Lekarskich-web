<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Moje usługi
        </h2>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1180px; margin: 0 auto;">

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

            @if ($message)
                <div style="background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;">
                    {{ $message }}
                </div>
            @endif

            @if ($doctor)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 34px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                    <p style="color: #059669; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                        Panel lekarza
                    </p>

                    <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                        Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </h1>

                    <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 850px;">
                        Tutaj możesz zarządzać usługami widocznymi dla pacjentów podczas rezerwacji wizyty.
                        Każda usługa ma własną klinikę, cenę, czas trwania oraz opis.
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Dodaj nową usługę
                    </h2>

                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 26px;">
                        Dodaj usługę, którą pacjent będzie mógł wybrać podczas umawiania wizyty.
                    </p>

                    @if ($clinics->count() > 0)
                        <form method="POST" action="{{ route('doctor.services.store') }}" style="display: flex; flex-direction: column; gap: 24px;">
                            @csrf

                            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px;">
                                <div>
                                    <label for="clinic_id" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                        Klinika
                                    </label>

                                    <select
                                        id="clinic_id"
                                        name="clinic_id"
                                        required
                                        style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                    >
                                        <option value="">Wybierz klinikę</option>

                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->id }}" @selected(old('clinic_id') == $clinic->id)>
                                                {{ $clinic->name }} — {{ $clinic->city }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                                        Po wybraniu kliniki system automatycznie przypisze Cię do tej kliniki.
                                    </p>
                                </div>

                                <div>
                                    <label for="name" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                        Nazwa usługi
                                    </label>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="np. Konsultacja kardiologiczna"
                                        required
                                        style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                    >
                                </div>

                                <div>
                                    <label for="price" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                        Cena
                                    </label>

                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <input
                                            type="number"
                                            id="price"
                                            name="price"
                                            value="{{ old('price') }}"
                                            min="0"
                                            step="0.01"
                                            placeholder="np. 150.00"
                                            required
                                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                        >

                                        <span style="font-size: 14px; font-weight: 800; color: #6b7280;">
                                            zł
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label for="duration_minutes" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                        Czas trwania
                                    </label>

                                    <select
                                        id="duration_minutes"
                                        name="duration_minutes"
                                        required
                                        style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                    >
                                        <option value="15" @selected(old('duration_minutes') == 15)>15 min</option>
                                        <option value="20" @selected(old('duration_minutes') == 20)>20 min</option>
                                        <option value="30" @selected(old('duration_minutes', 30) == 30)>30 min</option>
                                        <option value="45" @selected(old('duration_minutes') == 45)>45 min</option>
                                        <option value="60" @selected(old('duration_minutes') == 60)>60 min</option>
                                        <option value="90" @selected(old('duration_minutes') == 90)>90 min</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="description" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                    Opis usługi
                                </label>

                                <textarea
                                    id="description"
                                    name="description"
                                    rows="4"
                                    placeholder="Krótki opis usługi widoczny dla pacjenta."
                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 14px; font-size: 14px; resize: vertical;"
                                >{{ old('description') }}</textarea>
                            </div>

                            <div style="display: flex; align-items: center; gap: 18px;">
                                <button
                                    type="submit"
                                    style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 26px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 8px 16px rgba(37, 99, 235, 0.18);"
                                >
                                    Dodaj usługę
                                </button>
                            </div>
                        </form>
                    @else
                        <div style="background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 16px 20px; border-radius: 14px;">
                            Brak klinik w systemie. Administrator musi najpierw dodać przynajmniej jedną klinikę.
                        </div>
                    @endif
                </div>

                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <div style="padding: 26px 30px; border-bottom: 1px solid #e5e7eb;">
                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                            Lista usług
                        </h2>

                        <p style="font-size: 14px; color: #6b7280;">
                            Edytuj ceny, czas trwania, opis oraz klinikę przypisaną do usługi.
                        </p>
                    </div>

                    @if ($services->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 0;">
                            @foreach ($services as $service)
                                <div style="padding: 28px 30px; border-bottom: 1px solid #f3f4f6;">
                                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; margin-bottom: 22px;">
                                        <div>
                                            <h3 style="font-size: 20px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                                                {{ $service->name }}
                                            </h3>

                                            <p style="font-size: 14px; color: #6b7280;">
                                                {{ $service->clinic->name ?? 'Brak kliniki' }} — {{ $service->clinic->city ?? '' }}
                                            </p>
                                        </div>

                                        <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: flex-end;">
                                            <span style="padding: 7px 12px; background: #ecfdf5; color: #047857; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                {{ number_format($service->price, 2) }} zł
                                            </span>

                                            <span style="padding: 7px 12px; background: #eff6ff; color: #1d4ed8; border-radius: 999px; font-size: 12px; font-weight: 900;">
                                                {{ $service->duration_minutes }} min
                                            </span>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('doctor.services.update', $service) }}" style="display: flex; flex-direction: column; gap: 22px;">
                                        @csrf
                                        @method('PUT')

                                        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px;">
                                            <div>
                                                <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                                    Klinika
                                                </label>

                                                <select
                                                    name="clinic_id"
                                                    required
                                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                                >
                                                    @foreach ($clinics as $clinic)
                                                        <option value="{{ $clinic->id }}" @selected($service->clinic_id == $clinic->id)>
                                                            {{ $clinic->name }} — {{ $clinic->city }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                                                    Zmiana kliniki automatycznie przypisze Cię do wybranej kliniki.
                                                </p>
                                            </div>

                                            <div>
                                                <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                                    Nazwa usługi
                                                </label>

                                                <input
                                                    type="text"
                                                    name="name"
                                                    value="{{ $service->name }}"
                                                    required
                                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                                >
                                            </div>

                                            <div>
                                                <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                                    Cena
                                                </label>

                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <input
                                                        type="number"
                                                        name="price"
                                                        value="{{ $service->price }}"
                                                        min="0"
                                                        step="0.01"
                                                        required
                                                        style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                                    >

                                                    <span style="font-size: 14px; font-weight: 800; color: #6b7280;">
                                                        zł
                                                    </span>
                                                </div>
                                            </div>

                                            <div>
                                                <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                                    Czas trwania
                                                </label>

                                                <select
                                                    name="duration_minutes"
                                                    required
                                                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                                >
                                                    <option value="15" @selected($service->duration_minutes == 15)>15 min</option>
                                                    <option value="20" @selected($service->duration_minutes == 20)>20 min</option>
                                                    <option value="30" @selected($service->duration_minutes == 30)>30 min</option>
                                                    <option value="45" @selected($service->duration_minutes == 45)>45 min</option>
                                                    <option value="60" @selected($service->duration_minutes == 60)>60 min</option>
                                                    <option value="90" @selected($service->duration_minutes == 90)>90 min</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                                Opis usługi
                                            </label>

                                            <textarea
                                                name="description"
                                                rows="4"
                                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 14px; font-size: 14px; resize: vertical;"
                                            >{{ $service->description }}</textarea>
                                        </div>

                                        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 18px;">
                                            <button
                                                type="submit"
                                                style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 22px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 10px; border: none; cursor: pointer;"
                                            >
                                                Zapisz zmiany
                                            </button>
                                    </form>

                                            @if (! $service->appointments()->exists())
                                                <form method="POST" action="{{ route('doctor.services.delete', $service) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Czy na pewno chcesz usunąć tę usługę?')"
                                                        style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 22px; background: #fee2e2; color: #b91c1c; font-size: 14px; font-weight: 900; border-radius: 10px; border: none; cursor: pointer;"
                                                    >
                                                        Usuń usługę
                                                    </button>
                                                </form>
                                            @else
                                                <span style="font-size: 14px; color: #9ca3af;">
                                                    Nie można usunąć — usługa była użyta w wizycie.
                                                </span>
                                            @endif
                                        </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="padding: 32px;">
                            <p style="color: #6b7280;">
                                Nie masz jeszcze żadnych usług.
                            </p>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
