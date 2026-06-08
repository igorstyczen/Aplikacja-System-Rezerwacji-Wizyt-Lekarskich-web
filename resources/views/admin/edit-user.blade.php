<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edycja użytkownika
            </h2>

            <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Wróć do listy użytkowników
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-6">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $user->name }}
                </h1>

                <p class="text-gray-600 mt-1">
                    Edycja danych konta użytkownika.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Imię / nazwa
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Numer telefonu
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            placeholder="np. 123456789"
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                        >
                    </div>

                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                            Rola
                        </label>

                        @if ($user->id === Auth::id())
                            <input type="hidden" name="role" value="admin">

                            <select
                                id="role"
                                disabled
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm bg-gray-100"
                            >
                                <option value="admin" selected>admin</option>
                            </select>

                            <p class="text-xs text-gray-500 mt-1">
                                Nie możesz zmienić własnej roli, aby nie utracić dostępu do panelu administratora.
                            </p>
                        @else
                            <select
                                id="role"
                                name="role"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                            >
                                <option value="patient" @selected(old('role', $user->role) === 'patient')>
                                    patient
                                </option>
                                <option value="doctor" @selected(old('role', $user->role) === 'doctor')>
                                    doctor
                                </option>
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>
                                    admin
                                </option>
                            </select>
                        @endif
                    </div>

                    <div class="flex gap-3">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                        >
                            Zapisz zmiany
                        </button>

                        <a
                            href="{{ route('admin.users') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200"
                        >
                            Anuluj
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
