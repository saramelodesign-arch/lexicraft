<?php

namespace App\Support\Learning;

use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Support\SemanticGraph;
use Illuminate\Support\Collection;

/**
 * Builds lightweight flashcard payloads from published glossary content (no flashcard table).
 */
final class LearningFlashcardDeck
{
    /**
     * @return Collection<int, array{
     *     term: string,
     *     definition: string,
     *     examples: list<string>,
     *     domains: list<string>,
     *     semantic_hints: list<string>,
     *     glossary_url: string,
     * }>
     */
    public static function forLocale(string $locale, ?int $domainId = null, int $limit = 48): Collection
    {
        $languageId = Language::activeIdForCode($locale);
        if ($languageId === null) {
            return collect();
        }

        $query = ConceptTranslation::query()
            ->forPublishedLocale($locale)
            ->with([
                'concept.domains.translations' => fn ($q) => $q->where('language_id', $languageId),
                'examples' => fn ($q) => $q->orderBy('sort_order')->limit(4),
            ])
            ->inRandomOrder()
            ->limit($limit);

        if ($domainId !== null) {
            $query->whereHas('concept.domains', fn ($q) => $q->where('domains.id', $domainId));
        }

        return $query->get()->map(fn (ConceptTranslation $t): array => self::toCard($t, $languageId));
    }

    /**
     * @return array{
     *     term: string,
     *     definition: string,
     *     examples: list<string>,
     *     domains: list<string>,
     *     semantic_hints: list<string>,
     *     glossary_url: string,
     * }
     */
    public static function toCard(ConceptTranslation $translation, int $languageId): array
    {
        $concept = $translation->concept;
        if ($concept === null) {
            return [
                'term' => $translation->term,
                'definition' => (string) ($translation->short_definition ?? $translation->full_definition ?? ''),
                'examples' => [],
                'domains' => [],
                'semantic_hints' => [],
                'glossary_url' => $translation->glossaryUrl(absolute: false),
            ];
        }

        $concept->loadMissing([
            'outgoingRelations.relatedConcept.translations' => fn ($q) => $q->where('language_id', $languageId),
            'incomingRelations.concept.translations' => fn ($q) => $q->where('language_id', $languageId),
        ]);

        $grouped = SemanticGraph::peerTranslationsGrouped($concept, $languageId);
        $hints = [];
        foreach (['synonyms', 'broader', 'narrower', 'related'] as $bucket) {
            /** @var Collection<int, ConceptTranslation> $rows */
            $rows = $grouped[$bucket] ?? collect();
            foreach ($rows->take(3) as $peer) {
                $label = match ($bucket) {
                    'synonyms' => __('Synonym: :t', ['t' => $peer->term]),
                    'broader' => __('Broader: :t', ['t' => $peer->term]),
                    'narrower' => __('Narrower: :t', ['t' => $peer->term]),
                    default => __('Related: :t', ['t' => $peer->term]),
                };
                $hints[] = $label;
            }
        }

        $domains = [];
        foreach ($concept->domains as $d) {
            $dt = $d->translations->firstWhere('language_id', $languageId);
            if ($dt !== null) {
                $domains[] = $dt->name;
            }
        }

        $examples = $translation->examples
            ->pluck('example')
            ->filter(fn ($x) => is_string($x) && trim($x) !== '')
            ->map(fn (string $x): string => trim($x))
            ->values()
            ->take(4)
            ->all();

        $definition = (string) (
            $translation->short_definition
            ?? $translation->full_definition
            ?? ''
        );

        return [
            'term' => $translation->term,
            'definition' => $definition,
            'examples' => $examples,
            'domains' => array_values(array_unique($domains)),
            'semantic_hints' => array_values(array_unique(array_slice($hints, 0, 8))),
            'glossary_url' => $translation->glossaryUrl(absolute: false),
        ];
    }
}
