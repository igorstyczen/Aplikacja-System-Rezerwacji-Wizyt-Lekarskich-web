<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tagi pomocy
        </h2>
    </x-slot>

    <div style="padding: 40px 16px;">
        <div style="max-width: 1100px; margin: 0 auto;">

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
                <p style="color: #2563eb; font-size: 14px; font-weight: 900; margin-bottom: 8px;">
                    Panel administratora
                </p>

                <h1 style="font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 10px;">
                    Słownik tagów pomocy
                </h1>

                <p style="color: #4b5563; font-size: 15px; line-height: 1.7; max-width: 850px;">
                    Administrator zarządza tagami, które lekarze mogą przypisać do swojego profilu.
                    Tagi pomagają pacjentom znaleźć lekarza po problemie zdrowotnym, np. ból głowy, depresja albo problemy skórne.
                </p>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Dodaj tag
                </h2>

                <p style="font-size: 14px; color: #6b7280; margin-bottom: 22px;">
                    Dodaj nowy tag pomocy. Nazwa nie może się powtarzać.
                </p>

                <form method="POST" action="{{ route('admin.help-tags.store') }}" style="display: flex; align-items: center; gap: 16px;">
                    @csrf

                    <input
                        type="text"
                        name="tag_name"
                        value="{{ old('tag_name') }}"
                        placeholder="np. ból głowy"
                        required
                        style="flex: 1; border: 1px solid #d1d5db; border-radius: 12px; padding: 12px 14px; font-size: 14px;"
                    >

                    <button
                        type="submit"
                        style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer;"
                    >
                        Dodaj
                    </button>
                </form>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; padding: 32px; margin-bottom: 28px; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Filtry
                </h2>

                <p style="font-size: 14px; color: #6b7280; margin-bottom: 22px;">
                    Wyszukaj tag po nazwie albo sprawdź, które tagi są używane przez lekarzy.
                </p>

                <form method="GET" action="{{ route('admin.help-tags') }}" style="display: flex; flex-direction: column; gap: 22px;">
                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px;">
                        <div>
                            <label for="tag_name" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Nazwa tagu
                            </label>

                            <input
                                type="text"
                                id="tag_name"
                                name="tag_name"
                                value="{{ request('tag_name') }}"
                                placeholder="np. ból głowy"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                        </div>

                        <div>
                            <label for="usage" style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Przypisanie do lekarzy
                            </label>

                            <select
                                id="usage"
                                name="usage"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                            >
                                <option value="">Wszystkie</option>
                                <option value="used" @selected(request('usage') === 'used')>
                                    Używane przez lekarzy
                                </option>
                                <option value="unused" @selected(request('usage') === 'unused')>
                                    Nieprzypisane do lekarzy
                                </option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 18px;">
                        <button
                            type="submit"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 24px; background: #2563eb; color: white; font-size: 14px; font-weight: 900; border-radius: 12px; border: none; cursor: pointer;"
                        >
                            Filtruj
                        </button>

                        <a
                            href="{{ route('admin.help-tags') }}"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 24px; background: #f3f4f6; color: #374151; font-size: 14px; font-weight: 800; border-radius: 12px; text-decoration: none;"
                        >
                            Wyczyść
                        </a>
                    </div>
                </form>
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);">
                @if ($helpTags->count() > 0)
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 850px;">
                            <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Nazwa
                                    </th>

                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Przypisani lekarze
                                    </th>

                                    <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 900; color: #6b7280; text-transform: uppercase;">
                                        Akcje
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($helpTags as $helpTag)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 18px;">
                                            <form method="POST" action="{{ route('admin.help-tags.update', $helpTag) }}" style="display: flex; align-items: center; gap: 14px;">
                                                @csrf
                                                @method('PUT')

                                                <input
                                                    type="text"
                                                    name="tag_name"
                                                    value="{{ $helpTag->tag_name }}"
                                                    required
                                                    style="width: 100%; max-width: 360px; border: 1px solid #d1d5db; border-radius: 12px; padding: 11px 14px; font-size: 14px;"
                                                >

                                                <button
                                                    type="submit"
                                                    style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 18px; background: #dbeafe; color: #1d4ed8; font-size: 13px; font-weight: 900; border-radius: 10px; border: none; cursor: pointer;"
                                                >
                                                    Zapisz
                                                </button>
                                            </form>
                                        </td>

                                        <td style="padding: 18px; font-size: 14px; color: #374151;">
                                            <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 42px; padding: 7px 12px; background: #f3f4f6; color: #374151; border-radius: 999px; font-size: 13px; font-weight: 900;">
                                                {{ $helpTag->doctors_count }}
                                            </span>
                                        </td>

                                        <td style="padding: 18px;">
                                            @if ($helpTag->doctors_count == 0)
                                                <form method="POST" action="{{ route('admin.help-tags.delete', $helpTag) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Czy na pewno usunąć ten tag?')"
                                                        style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 18px; background: #fee2e2; color: #b91c1c; font-size: 13px; font-weight: 900; border-radius: 10px; border: none; cursor: pointer;"
                                                    >
                                                        Usuń
                                                    </button>
                                                </form>
                                            @else
                                                <span style="font-size: 13px; color: #9ca3af; font-weight: 700;">
                                                    Nie można usunąć — tag jest używany.
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="padding: 32px;">
                        <p style="color: #6b7280;">
                            Brak tagów pomocy spełniających wybrane filtry.
                        </p>
                    </div>
                @endif
            </div>

            <div style="margin-top: 28px;">
                {{ $helpTags->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
