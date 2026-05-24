<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Zgłoszenia lekarzy
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

            <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Zgłoszenia o profil lekarza
                </h1>

                <p class="text-gray-600">
                    Administrator może zaakceptować lub odrzucić zgłoszenie użytkownika, który chce zostać lekarzem.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                @if ($applications->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach ($applications as $application)
                            <div class="p-6">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-900">
                                            Dr {{ $application->first_name }} {{ $application->last_name }}
                                        </h2>

                                        <p class="text-sm text-gray-500">
                                            Użytkownik: {{ $application->user->name }} — {{ $application->user->email }}
                                        </p>

                                        @if ($application->phone)
                                            <p class="text-sm text-gray-500">
                                                Telefon: {{ $application->phone }}
                                            </p>
                                        @endif

                                        <p class="text-sm text-gray-700 mt-3">
                                            {{ $application->bio }}
                                        </p>

                                        <div class="flex flex-wrap gap-2 mt-3">
                                            @if ($application->is_for_adults)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">
                                                    Dorośli
                                                </span>
                                            @endif

                                            @if ($application->is_for_children)
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                                    Dzieci
                                                </span>
                                            @endif
                                        </div>

                                        @if ($application->admin_note)
                                            <p class="text-sm text-gray-500 mt-3">
                                                Notatka admina: {{ $application->admin_note }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="min-w-[220px]">
                                        @if ($application->status === 'pending')
                                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold mb-3">
                                                Oczekuje
                                            </span>

                                            <form method="POST" action="{{ route('admin.doctor-applications.approve', $application) }}" class="mb-3">
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    style="width: 100%; padding: 9px 16px; background: #059669; color: white; font-size: 14px; font-weight: 700; border-radius: 8px; border: none; cursor: pointer;"
                                                >
                                                    Akceptuj
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.doctor-applications.reject', $application) }}" class="space-y-2">
                                                @csrf
                                                @method('PATCH')

                                                <textarea
                                                    name="admin_note"
                                                    rows="2"
                                                    placeholder="Powód odrzucenia"
                                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                                ></textarea>

                                                <button
                                                    type="submit"
                                                    style="width: 100%; padding: 9px 16px; background: #fee2e2; color: #b91c1c; font-size: 14px; font-weight: 700; border-radius: 8px; border: none; cursor: pointer;"
                                                >
                                                    Odrzuć
                                                </button>
                                            </form>
                                        @elseif ($application->status === 'approved')
                                            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                                Zaakceptowane
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">
                                                Odrzucone
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6">
                        <p class="text-gray-600">
                            Brak zgłoszeń.
                        </p>
                    </div>
                @endif
            </div>

            <div class="mt-6">
                {{ $applications->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
