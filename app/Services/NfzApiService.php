<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NfzApiService
{
    private string $baseUrl = 'https://api.nfz.gov.pl/app-itl-api';

    public function searchQueues(string $benefit, string $locality, string $province, int $case = 1): array
    {
        try {
            $response = Http::timeout(7)
                ->acceptJson()
                ->get($this->baseUrl . '/queues', [
                    'page' => 1,
                    'limit' => 25,
                    'format' => 'json',
                    'case' => $case,
                    'province' => $province,
                    'benefit' => $benefit,
                    'locality' => $locality,
                ]);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Nie udało się połączyć z API NFZ.',
                'items' => [],
                'nearest' => null,
            ];
        }

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'API NFZ nie zwróciło poprawnej odpowiedzi.',
                'items' => [],
                'nearest' => null,
            ];
        }

        $items = collect($response->json('data', []))
        ->map(function ($item) {
            $attributes = $item['attributes'] ?? [];

            $dateValue = data_get($attributes, 'dates.date');

            return [
                'id' => $item['id'] ?? null,
                'benefit' => $attributes['benefit'] ?? 'Brak danych',
                'provider' => $attributes['provider'] ?? 'Brak danych',
                'place' => $attributes['place'] ?? 'Brak danych',
                'address' => $attributes['address'] ?? 'Brak danych',
                'locality' => $attributes['locality'] ?? 'Brak danych',
                'phone' => $attributes['phone'] ?? null,
                'date' => $dateValue,
                'waiting_count' => data_get($attributes, 'statistics.provider-data.awaiting'),
                'average_waiting_days' => data_get($attributes, 'statistics.provider-data.average-period')
                    ?? data_get($attributes, 'statistics.computed-data.average-period'),
            ];
        })
        ->filter(function ($item) {
            if (empty($item['date'])) {
                return false;
            }

            try {
                return Carbon::parse($item['date'])->startOfDay()
                    ->greaterThanOrEqualTo(now()->startOfDay());
            } catch (\Exception $e) {
                return false;
            }
        })
        ->sortBy(function ($item) {
            return Carbon::parse($item['date']);
        })
        ->values()
        ->all();

        return [
            'success' => true,
            'message' => null,
            'items' => $items,
            'nearest' => $items[0] ?? null,
        ];
    }

    public function calculateDifferenceInDays(?string $privateDate, ?string $nfzDate): ?int
    {
        if (! $privateDate || ! $nfzDate) {
            return null;
        }

        return Carbon::parse($privateDate)->startOfDay()
            ->diffInDays(Carbon::parse($nfzDate)->startOfDay(), false);
    }
}
