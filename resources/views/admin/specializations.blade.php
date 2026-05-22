<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Specjalizacje
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

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
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Słownik specjalizacji
                </h1>

                <p class="text-gray-600">
                    Administrator zarządza listą specjalizacji, które lekarze mogą wybierać w swoim profilu.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Dodaj specjalizację
                </h2>

                <form method="POST" action="{{ route('admin.specializations.store') }}" class="flex gap-3">
                    @csrf

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="np. Kardiolog"
                        class="flex-1 border-gray-300 rounded-md shadow-sm text-sm"
                        required
                    >

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                    >
                        Dodaj
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                @if ($specializations->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nazwa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Przypisania do lekarzy</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akcje</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($specializations as $specialization)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <form method="POST" action="{{ route('admin.specializations.update', $specialization) }}" class="flex gap-2">
                                            @csrf
                                            @method('PUT')

                                            <input
                                                type="text"
                                                name="name"
                                                value="{{ old('name', $specialization->name) }}"
                                                class="border-gray-300 rounded-md shadow-sm text-sm w-full"
                                                required
                                            >

                                            <button
                                                type="submit"
                                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200"
                                            >
                                                Zapisz
                                            </button>
                                        </form>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $specialization->doctor_specializations_count }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($specialization->doctor_specializations_count == 0)
                                            <form method="POST" action="{{ route('admin.specializations.delete', $specialization) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Czy na pewno usunąć tę specjalizację?')"
                                                    class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200"
                                                >
                                                    Usuń
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs">
                                                Nie można usunąć, bo jest używana
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
                            Brak specjalizacji w słowniku.
                        </p>
                    </div>
                @endif
            </div>

            <div class="mt-6">
                {{ $specializations->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
