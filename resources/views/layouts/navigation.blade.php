<nav x-data="{ open: false }" class="bg-white/90 backdrop-blur border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 me-8">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #ecfdf5;">
                            <x-application-logo class="block h-7 w-auto fill-current text-emerald-700" />
                        </div>

                        <span class="hidden lg:block font-bold text-gray-900">
                            Medical Booking
                        </span>
                    </a>
                </div>

                <div class="hidden sm:-my-px sm:ms-16 sm:flex items-center" style="gap: 34px;">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Strona główna
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Panel
                        </x-nav-link>

                        @if (Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                Admin
                            </x-nav-link>

                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                                Użytkownicy
                            </x-nav-link>

                            <x-nav-link :href="route('admin.doctors')" :active="request()->routeIs('admin.doctors')">
                                Lekarze
                            </x-nav-link>

                            <x-nav-link :href="route('admin.appointments')" :active="request()->routeIs('admin.appointments')">
                                Wizyty
                            </x-nav-link>
                        @endif

                        @if (Auth::user()->role === 'patient')
                            <x-nav-link :href="route('patient.appointments')" :active="request()->routeIs('patient.appointments')">
                                Moje wizyty
                            </x-nav-link>

                            <x-nav-link :href="route('doctor-applications.create')" :active="request()->routeIs('doctor-applications.*')">
                                Załóż profil lekarza
                            </x-nav-link>
                        @endif

                        @if (Auth::user()->role === 'doctor')
                            <x-nav-link :href="route('doctor.schedule')" :active="request()->routeIs('doctor.schedule')">
                                Mój grafik
                            </x-nav-link>

                            <x-nav-link :href="route('doctor.services')" :active="request()->routeIs('doctor.services')">
                                Moje usługi
                            </x-nav-link>

                            <x-nav-link :href="route('doctor.profile')" :active="request()->routeIs('doctor.profile')">
                                Profil lekarza
                            </x-nav-link>

                            <x-nav-link :href="route('doctor.appointments')" :active="request()->routeIs('doctor.appointments')">
                                Wizyty pacjentów
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-16">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-gray-100 text-sm leading-4 font-medium rounded-xl text-gray-600 bg-white hover:text-gray-900 hover:shadow-sm focus:outline-none transition">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </div>

                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profil
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    Wyloguj
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-emerald-700 font-medium">
                            Logowanie
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold text-white shadow-sm"
                            style="background: #059669;"
                        >
                            Rejestracja
                        </a>
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button
                    @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Strona główna
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Panel
                </x-responsive-nav-link>

                @if (Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Admin
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                        Użytkownicy
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.doctors')" :active="request()->routeIs('admin.doctors')">
                        Lekarze
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.appointments')" :active="request()->routeIs('admin.appointments')">
                        Wizyty
                    </x-responsive-nav-link>
                @endif

                @if (Auth::user()->role === 'patient')
                    <x-responsive-nav-link :href="route('patient.appointments')" :active="request()->routeIs('patient.appointments')">
                        Moje wizyty
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('doctor-applications.create')" :active="request()->routeIs('doctor-applications.*')">
                        Załóż profil lekarza
                    </x-responsive-nav-link>
                @endif

                @if (Auth::user()->role === 'doctor')
                    <x-responsive-nav-link :href="route('doctor.schedule')" :active="request()->routeIs('doctor.schedule')">
                        Mój grafik
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('doctor.services')" :active="request()->routeIs('doctor.services')">
                        Moje usługi
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('doctor.profile')" :active="request()->routeIs('doctor.profile')">
                        Profil lekarza
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('doctor.appointments')" :active="request()->routeIs('doctor.appointments')">
                        Wizyty pacjentów
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    <div class="font-medium text-xs text-emerald-600 mt-1">
                        Rola: {{ Auth::user()->role }}
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profil
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link
                            :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                        >
                            Wyloguj
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        Logowanie
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('register')">
                        Rejestracja
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
