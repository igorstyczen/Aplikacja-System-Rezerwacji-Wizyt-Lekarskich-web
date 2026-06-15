@props(['paginator', 'itemLabel' => 'pozycji'])

@if ($paginator->total() > $paginator->perPage())
    <div style="padding: 18px 30px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
        <p style="font-size: 14px; color: #374151; margin: 0; font-weight: 700;">
            Wyświetlanie {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} z {{ $paginator->total() }} {{ $itemLabel }}
            <span style="color: #6b7280; font-weight: 600;">(strona {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }})</span>
        </p>

        <div style="display: flex; align-items: center; gap: 10px;">
            @if ($paginator->onFirstPage())
                <span style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 16px; background: #e5e7eb; color: #9ca3af; border-radius: 10px; font-size: 13px; font-weight: 900; cursor: not-allowed;">
                    ← Poprzednia
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 16px; background: #f3f4f6; color: #374151; border-radius: 10px; font-size: 13px; font-weight: 900; text-decoration: none;"
                >
                    ← Poprzednia
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 16px; background: #2563eb; color: white; border-radius: 10px; font-size: 13px; font-weight: 900; text-decoration: none; box-shadow: 0 6px 14px rgba(37, 99, 235, 0.22);"
                >
                    Następna →
                </a>
            @else
                <span style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 16px; background: #e5e7eb; color: #9ca3af; border-radius: 10px; font-size: 13px; font-weight: 900; cursor: not-allowed;">
                    Następna →
                </span>
            @endif
        </div>
    </div>
@endif
