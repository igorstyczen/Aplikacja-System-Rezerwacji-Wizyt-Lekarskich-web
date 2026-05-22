<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Moje usługi
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

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

            @if ($doctor)
                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Dr {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </h1>

                    <p class="text-gray-600 mt-1">
                        Tutaj możesz zarządzać usługami widocznymi dla pacjentów podczas rezerwacji wizyty.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Dodaj nową usługę
                    </h2>

                    @if ($clinics->count() > 0)
                        <form method="POST" action="{{ route('doctor.services.store') }}" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="clinic_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Klinika
                                    </label>

                                    <select
                                        id="clinic_id"
                                        name="clinic_id"
                                        required
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                    >
                                        <option value="">Wybierz klinikę</option>

                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->id }}" @selected(old('clinic_id') == $clinic->id)>
                                                {{ $clinic->name }} — {{ $clinic->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nazwa usługi
                                    </label>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="np. Konsultacja kardiologiczna"
                                        required
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                    >
                                </div>

                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                        Cena
                                    </label>

                                    <input
                                        type="number"
                                        id="price"
                                        name="price"
                                        value="{{ old('price') }}"
                                        min="0"
                                        step="0.01"
                                        placeholder="np. 150.00"
                                        required
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                    >
                                </div>

                                <div>
                                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Czas trwania
                                    </label>

                                    <select
                                        id="duration_minutes"
                                        name="duration_minutes"
                                        required
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm"
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
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Opis usługi
                                </label>

                                <textarea
                                    id="description"
                                    name="description"
                                    rows="3"
                                    placeholder="Krótki opis usługi widoczny dla pacjenta."
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                >{{ old('description') }}</textarea>
                            </div>

                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                            >
                                Dodaj usługę
                            </button>
                        </form>
                    @else
                        <p class="text-gray-600">
                            Nie masz przypisanej żadnej kliniki. Najpierw administrator musi przypisać klinikę do Twojego profilu.
                        </p>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Lista usług
                        </h2>
                    </div>

                    @if ($services->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach ($services as $service)
                                <div class="p-6">
                                    <form method="POST" action="{{ route('doctor.services.update', $service) }}" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Klinika
                                                </label>

                                                <select
                                                    name="clinic_id"
                                                    required
                                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                                >
                                                    @foreach ($clinics as $clinic)
                                                        <option value="{{ $clinic->id }}" @selected($service->clinic_id == $clinic->id)>
                                                            {{ $clinic->name }} — {{ $clinic->city }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Nazwa usługi
                                                </label>

                                                <input
                                                    type="text"
                                                    name="name"
                                                    value="{{ $service->name }}"
                                                    required
                                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                                >
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Cena
                                                </label>

                                                <input
                                                    type="number"
                                                    name="price"
                                                    value="{{ $service->price }}"
                                                    min="0"
                                                    step="0.01"
                                                    required
                                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                                >
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Czas trwania
                                                </label>

                                                <select
                                                    name="duration_minutes"
                                                    required
                                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm"
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
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Opis usługi
                                            </label>

                                            <textarea
                                                name="description"
                                                rows="3"
                                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                            >{{ $service->description }}</textarea>
                                        </div>

                                        <div class="flex flex-wrap gap-3">
                                            <button
                                                type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
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
                                                        class="px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-md hover:bg-red-200"
                                                    >
                                                        Usuń
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-sm text-gray-400">
                                                    Nie można usunąć — usługa była użyta w wizycie.
                                                </span>
                                            @endif
                                        </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6">
                            <p class="text-gray-600">
                                Nie masz jeszcze żadnych usług.
                            </p>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
