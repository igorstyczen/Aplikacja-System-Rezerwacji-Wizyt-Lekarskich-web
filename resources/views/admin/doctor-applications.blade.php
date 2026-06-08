<x-app-layout>
    @php
        $specializations = isset($specializations)
            ? collect($specializations)
            : \App\Models\Specialization::query()->orderBy('name')->get();

        $helpTags = isset($helpTags)
            ? collect($helpTags)
            : \App\Models\HelpTag::query()->orderBy('tag_name')->get();
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Zgłoszenia lekarzy
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

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; margin-bottom: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);">
                <p style="color: #059669; font-size: 14px; font-weight: 800; margin-bottom: 8px;">
                    Panel administratora
                </p>

                <h1 style="font-size: 30px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                    Zgłoszenia o profil lekarza
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7;">
                    Tutaj administrator może zaakceptować lub odrzucić zgłoszenia użytkowników, którzy chcą zostać lekarzami.
                </p>
            </div>

            @if ($applications->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 22px;">
                    @foreach ($applications as $application)
                        @php
                            $rawSpecializationIds = $application->specialization_ids;

                            if (is_string($rawSpecializationIds)) {
                                $rawSpecializationIds = json_decode($rawSpecializationIds, true) ?? [];
                            }

                            $selectedSpecializationIds = collect($rawSpecializationIds ?? [])
                                ->map(fn ($id) => (int) $id)
                                ->values();

                            $applicationSpecializations = collect($specializations)
                                ->filter(fn ($specialization) => $selectedSpecializationIds->contains((int) $specialization->id))
                                ->pluck('name')
                                ->values();

                            $rawHelpTagIds = $application->help_tag_ids;

                            if (is_string($rawHelpTagIds)) {
                                $rawHelpTagIds = json_decode($rawHelpTagIds, true) ?? [];
                            }

                            $selectedHelpTagIds = collect($rawHelpTagIds ?? [])
                                ->map(fn ($id) => (int) $id)
                                ->values();

                            $applicationHelpTags = collect($helpTags)
                                ->filter(fn ($tag) => $selectedHelpTagIds->contains((int) $tag->id))
                                ->pluck('tag_name')
                                ->values();
                        @endphp

                        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                            <div style="display: grid; grid-template-columns: minmax(0, 1fr) 260px; gap: 28px; align-items: start;">

                                <div>
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                                        <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin: 0;">
                                            Dr {{ $application->first_name }} {{ $application->last_name }}
                                        </h2>

                                        @if ($application->status === 'pending')
                                            <span style="padding: 5px 10px; background: #fef3c7; color: #92400e; border-radius: 999px; font-size: 12px; font-weight: 800;">
                                                Oczekuje
                                            </span>
                                        @elseif ($application->status === 'approved')
                                            <span style="padding: 5px 10px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 800;">
                                                Zaakceptowane
                                            </span>
                                        @else
                                            <span style="padding: 5px 10px; background: #fee2e2; color: #991b1b; border-radius: 999px; font-size: 12px; font-weight: 800;">
                                                Odrzucone
                                            </span>
                                        @endif
                                    </div>

                                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">
                                        Użytkownik: {{ $application->user->name }} — {{ $application->user->email }}
                                    </p>

                                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 18px;">
                                        Telefon: {{ $application->phone ?? 'Brak' }}
                                    </p>

                                    <div style="margin-bottom: 18px;">
                                        <h3 style="font-size: 14px; font-weight: 800; color: #374151; margin-bottom: 8px;">
                                            Specjalizacje
                                        </h3>

                                        @if ($applicationSpecializations->count() > 0)
                                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                                @foreach ($applicationSpecializations as $specializationName)
                                                    <span style="padding: 6px 10px; background: #ecfdf5; color: #047857; border-radius: 999px; font-size: 12px; font-weight: 800;">
                                                        {{ $specializationName }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p style="font-size: 14px; color: #9ca3af;">
                                                Brak wybranych specjalizacji.
                                            </p>
                                        @endif
                                    </div>

                                    <div style="margin-bottom: 18px;">
                                        <h3 style="font-size: 14px; font-weight: 800; color: #374151; margin-bottom: 8px;">
                                            Tagi / obszary pomocy
                                        </h3>

                                        @if ($applicationHelpTags->count() > 0)
                                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                                @foreach ($applicationHelpTags as $tagName)
                                                    <span style="padding: 6px 10px; background: #eff6ff; color: #1d4ed8; border-radius: 999px; font-size: 12px; font-weight: 800;">
                                                        {{ $tagName }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p style="font-size: 14px; color: #9ca3af;">
                                                Brak wybranych tagów.
                                            </p>
                                        @endif
                                    </div>

                                    <div style="margin-bottom: 18px;">
                                        <h3 style="font-size: 14px; font-weight: 800; color: #374151; margin-bottom: 8px;">
                                            Opis / bio
                                        </h3>

                                        <p style="font-size: 14px; color: #4b5563; line-height: 1.7; overflow-wrap: anywhere; word-break: break-word;">
                                            {{ $application->bio }}
                                        </p>
                                    </div>

                                    <div style="margin-bottom: 18px;">
                                        <h3 style="font-size: 14px; font-weight: 800; color: #374151; margin-bottom: 8px;">
                                            Miejsce przyjmowania
                                        </h3>

                                        <p style="font-size: 14px; color: #4b5563; margin-bottom: 4px;">
                                            <strong>{{ $application->clinic_name }}</strong>
                                        </p>

                                        <p style="font-size: 14px; color: #4b5563; margin-bottom: 4px;">
                                            {{ $application->clinic_address }}, {{ $application->clinic_city }}
                                        </p>

                                        @if ($application->clinic_details)
                                            <p style="font-size: 14px; color: #6b7280;">
                                                {{ $application->clinic_details }}
                                            </p>
                                        @endif
                                    </div>

                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                        @if ($application->is_for_adults)
                                            <span style="padding: 6px 10px; background: #dbeafe; color: #1d4ed8; border-radius: 999px; font-size: 12px; font-weight: 800;">
                                                Przyjmuje dorosłych
                                            </span>
                                        @endif

                                        @if ($application->is_for_children)
                                            <span style="padding: 6px 10px; background: #f0fdf4; color: #15803d; border-radius: 999px; font-size: 12px; font-weight: 800;">
                                                Przyjmuje dzieci
                                            </span>
                                        @endif
                                    </div>

                                    @if ($application->admin_note)
                                        <div style="margin-top: 18px; padding: 14px; background: #f9fafb; border-radius: 12px;">
                                            <p style="font-size: 14px; color: #4b5563;">
                                                <strong>Notatka admina:</strong> {{ $application->admin_note }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    @if ($application->status === 'pending')
                                        <form method="POST" action="{{ route('admin.doctor-applications.approve', $application) }}" style="margin-bottom: 14px;">
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                style="width: 100%; padding: 12px 18px; background: #059669; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer;"
                                            >
                                                Akceptuj zgłoszenie
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.doctor-applications.reject', $application) }}">
                                            @csrf
                                            @method('PATCH')

                                            <textarea
                                                name="admin_note"
                                                rows="4"
                                                placeholder="Powód odrzucenia"
                                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 12px; font-size: 14px; margin-bottom: 10px;"
                                            ></textarea>

                                            <button
                                                type="submit"
                                                style="width: 100%; padding: 12px 18px; background: #fee2e2; color: #b91c1c; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer;"
                                            >
                                                Odrzuć zgłoszenie
                                            </button>
                                        </form>
                                    @else
                                        <div style="padding: 16px; background: #f9fafb; border-radius: 14px; text-align: center;">
                                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 6px;">
                                                Zgłoszenie rozpatrzone
                                            </p>

                                            @if ($application->reviewed_at)
                                                <p style="font-size: 12px; color: #9ca3af;">
                                                    {{ $application->reviewed_at->format('d.m.Y H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 28px;">
                    {{ $applications->links() }}
                </div>
            @else
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 22px; padding: 32px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                    <p style="color: #6b7280;">
                        Brak zgłoszeń do wyświetlenia.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
