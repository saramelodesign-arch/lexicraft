<?php

namespace App\Support\Editorial;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class DuplicateDetectionService
{
    public static function normalizeTerm(string $term): string
    {
        $normalized = Str::of($term)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9\s-]+/u', ' ')
            ->replaceMatches('/[-_]+/u', ' ')
            ->squish()
            ->toString();

        return $normalized;
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

        $tokens = preg_split('/\s+/u', $normalized) ?: [];
        $seed = (string) ($tokens[0] ?? '');
        $candidateLike = mb_strlen($seed) >= 3 ? '%'.$seed.'%' : '%'.Str::substr($normalized, 0, 3).'%';

        $query = ConceptTranslation::query()
            ->where('language_id', $languageId)
            ->where(function ($q) use ($candidateLike, $normalized): void {
                $q->whereRaw('LOWER(term) like ?', [$candidateLike])
                    ->orWhereRaw('LOWER(slug) like ?', ['%'.str_replace(' ', '-', $normalized).'%']);
            })
            ->limit(150);

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

    /**
     * @return list<string>
     */
    public static function crossLocaleTermCollisionWarnings(Concept $concept): array
    {
        $groups = [];
        foreach ($concept->translations as $translation) {
            $normalized = self::normalizeTerm((string) $translation->term);
            if ($normalized === '' || mb_strlen($normalized) < 4) {
                continue;
            }
            $code = strtolower((string) ($translation->language?->code ?? ''));
            if ($code === '') {
                continue;
            }
            $groups[$normalized][] = $code;
        }

        $warnings = [];
        foreach ($groups as $normalized => $codes) {
            $uniqueCodes = array_values(array_unique($codes));
            if (count($uniqueCodes) < 2) {
                continue;
            }

            $warnings[] = sprintf(
                'Potential untranslated term mapping "%s" appears in locales: %s.',
                $normalized,
                strtoupper(implode(', ', $uniqueCodes))
            );
        }

        return $warnings;
    }
}
