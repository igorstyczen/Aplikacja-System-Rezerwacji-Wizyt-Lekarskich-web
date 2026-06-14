<?php

namespace App\Services;

use App\Models\HelpTag;
use Illuminate\Support\Collection;

class HelpTagSimilarityService
{
    public function similarity(string $a, string $b): float
    {
        $a = $this->normalize($a);
        $b = $this->normalize($b);

        if ($a === '' || $b === '') {
            return 0.0;
        }

        if ($a === $b) {
            return 1.0;
        }

        similar_text($a, $b, $percent);

        return round($percent / 100, 4);
    }

    public function findSimilar(string $tagName, float $threshold = 0.6): Collection
    {
        return HelpTag::query()
            ->orderBy('tag_name')
            ->get()
            ->map(function (HelpTag $tag) use ($tagName) {
                return [
                    'tag' => $tag,
                    'score' => $this->similarity($tagName, $tag->tag_name),
                ];
            })
            ->filter(fn (array $item) => $item['score'] >= $threshold)
            ->sortByDesc('score')
            ->values()
            ->map(fn (array $item) => $item['tag']);
    }

    public function findMatchingTagIds(string $search, float $threshold = 0.5): array
    {
        $normalizedSearch = $this->normalize($search);

        if ($normalizedSearch === '') {
            return [];
        }

        return HelpTag::query()
            ->get()
            ->filter(function (HelpTag $tag) use ($search, $normalizedSearch, $threshold) {
                if ($this->normalize($tag->tag_name) === $normalizedSearch) {
                    return true;
                }

                if (str_contains($this->normalize($tag->tag_name), $normalizedSearch)) {
                    return true;
                }

                return $this->similarity($search, $tag->tag_name) >= $threshold;
            })
            ->pluck('id')
            ->all();
    }

    private function normalize(string $value): string
    {
        return mb_strtolower(trim($value));
    }
}
