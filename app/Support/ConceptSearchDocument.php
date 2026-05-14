<?php

namespace App\Support;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use Illuminate\Support\Str;

/**
 * Canonical index payload for Scout + Meilisearch (one document per {@see ConceptTranslation}).
 *
 * Field names are stable for index settings, filters, and sync workers. Do not include a top-level
 * `id` key here — Scout supplies the primary key separately.
 */
final class ConceptSearchDocument
{
    public const int MAX_FULL_DEFINITION = 8000;

    public const int MAX_SEARCHABLE_TEXT = 28000;

    public const int MAX_EXAMPLES_SNIPPET = 1500;

    /**
     * @return array<string, mixed>
     */
    public static function fromTranslation(ConceptTranslation $translation): array
    {
        $translation->loadMissing(['language', 'concept', 'examples' => fn ($q) => $q->orderBy('sort_order')->limit(12)]);

        $concept = $translation->concept;
        if ($concept === null) {
            return [];
        }

        $concept->loadMissing(['domains.translations']);

        $languageCode = $translation->language?->code ?? '';
        $languageId = $translation->language_id;

        $domainNames = [];
        $domainSlugs = [];
        foreach ($concept->domains as $domain) {
            $dt = $domain->translations->firstWhere('language_id', $languageId);
            if ($dt !== null) {
                $domainNames[] = $dt->name;
                if (filled($dt->slug)) {
                    $domainSlugs[] = $dt->slug;
                }
            }
        }

        $relatedTerms = self::relatedTermsSameLanguage($concept, $languageId);
        $relationTypes = self::outgoingRelationTypes($concept);

        $synonyms = self::synonymTokens($translation->meta_keywords);

        $examplesSnippet = self::examplesSnippet($translation);

        $fullDef = $translation->full_definition;
        if (is_string($fullDef) && mb_strlen($fullDef, 'UTF-8') > self::MAX_FULL_DEFINITION) {
            $fullDef = mb_substr($fullDef, 0, self::MAX_FULL_DEFINITION, 'UTF-8').'…';
        }

        $isPublished = $concept->status === 'published'
            && ($translation->language?->is_active ?? false);

        $parts = array_filter([
            $translation->term,
            $translation->slug,
            implode(' ', $synonyms),
            $translation->short_definition,
            $fullDef,
            $translation->seo_title,
            $translation->seo_description,
            $translation->industry_notes,
            implode(' ', $domainNames),
            implode(' ', $relatedTerms),
            $examplesSnippet,
        ], static fn ($v): bool => is_string($v) && $v !== '');

        $searchableText = mb_strtolower(implode("\n", $parts), 'UTF-8');
        if (mb_strlen($searchableText, 'UTF-8') > self::MAX_SEARCHABLE_TEXT) {
            $searchableText = mb_substr($searchableText, 0, self::MAX_SEARCHABLE_TEXT, 'UTF-8');
        }

        return [
            'concept_translation_id' => $translation->id,
            'concept_id' => $concept->id,
            'concept_uuid' => $concept->uuid,
            'language_code' => $languageCode,
            'slug' => $translation->slug,
            'term' => $translation->term,
            'short_definition' => $translation->short_definition,
            'full_definition' => $fullDef,
            'is_published' => $isPublished,
            'status' => $concept->status,
            'domain_slugs' => array_values(array_unique($domainSlugs)),
            'domain_names' => array_values(array_unique($domainNames)),
            'related_terms' => array_values(array_unique($relatedTerms)),
            'relation_types' => array_values(array_unique($relationTypes)),
            'synonyms' => array_values(array_unique($synonyms)),
            'examples_snippet' => $examplesSnippet,
            'searchable_text' => $searchableText,
        ];
    }

    /**
     * @return list<string>
     */
    private static function synonymTokens(?string $metaKeywords): array
    {
        if ($metaKeywords === null || trim($metaKeywords) === '') {
            return [];
        }

        $parts = preg_split('/[,;|]+/u', $metaKeywords) ?: [];

        return array_values(array_unique(array_filter(array_map(
            static fn (string $s): string => trim($s),
            $parts,
        ), static fn (string $s): bool => $s !== '')));
    }

    private static function examplesSnippet(ConceptTranslation $translation): string
    {
        $lines = [];
        foreach ($translation->examples as $ex) {
            $t = trim((string) $ex->example);
            if ($t !== '') {
                $lines[] = $t;
            }
        }

        $joined = implode("\n", $lines);

        return Str::limit($joined, self::MAX_EXAMPLES_SNIPPET, '…');
    }

    /**
     * @return list<string>
     */
    private static function outgoingRelationTypes(Concept $concept): array
    {
        $concept->loadMissing(['outgoingRelations']);

        return $concept->outgoingRelations
            ->pluck('relation_type')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    private static function relatedTermsSameLanguage(Concept $concept, int $languageId): array
    {
        $concept->loadMissing([
            'outgoingRelations.relatedConcept.translations',
            'incomingRelations.concept.translations',
        ]);

        $out = [];
        foreach ($concept->outgoingRelations as $rel) {
            $tr = $rel->relatedConcept?->translations->firstWhere('language_id', $languageId);
            if ($tr !== null && $tr->term !== '') {
                $out[] = $tr->term;
            }
        }
        foreach ($concept->incomingRelations as $rel) {
            $tr = $rel->concept?->translations->firstWhere('language_id', $languageId);
            if ($tr !== null && $tr->term !== '') {
                $out[] = $tr->term;
            }
        }

        return $out;
    }
}
