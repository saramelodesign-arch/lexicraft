<?php

namespace App\Support\Editorial;

use App\Models\ConceptTranslation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class DuplicateDetectionService
{
    public static function normalizeTerm(string $term): string
    {
        return Str::of($term)->lower()->squish()->toString();
    }

    public static function findExactTermDuplicate(
        int $languageId,
        string $term,
        ?int $ignoreTranslationId = null,
        ?int $ignoreConceptId = null
    ): ?ConceptTranslation {
        $normalized = self::normalizeTerm($term);
        if ($normalized === '') {
            return null;
        }

        $query = ConceptTranslation::query()
            ->where('language_id', $languageId)
            ->whereRaw('LOWER(TRIM(term)) = ?', [$normalized]);

        if ($ignoreTranslationId !== null) {
            $query->whereKeyNot($ignoreTranslationId);
        }

        if ($ignoreConceptId !== null) {
            $query->where('concept_id', '!=', $ignoreConceptId);
        }

        return $query->first();
    }

    /**
     * @return Collection<int, ConceptTranslation>
     */
    public static function findNearDuplicates(int $languageId, string $term, ?int $ignoreConceptId = null): Collection
    {
        $normalized = self::normalizeTerm($term);
        if ($normalized === '') {
            return collect();
        }

        $prefix = Str::substr($normalized, 0, 3);
        $candidateLike = $prefix === '' ? '%' : $prefix.'%';

        $query = ConceptTranslation::query()
            ->where('language_id', $languageId)
            ->whereRaw('LOWER(term) like ?', [$candidateLike])
            ->limit(100);

        if ($ignoreConceptId !== null) {
            $query->where('concept_id', '!=', $ignoreConceptId);
        }

        return $query->get()
            ->filter(function (ConceptTranslation $candidate) use ($normalized): bool {
                $score = 0.0;
                similar_text($normalized, self::normalizeTerm($candidate->term), $score);

                return $score >= 85.0;
            })
            ->values();
    }
}
