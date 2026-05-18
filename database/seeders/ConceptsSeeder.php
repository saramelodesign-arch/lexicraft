<?php

namespace Database\Seeders;

use App\Models\Concept;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\Example;
use App\Models\Language;
use App\Support\Editorial\WorkflowStatus;
use App\Support\SemanticGraph;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConceptsSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array{concepts: list<array<string, mixed>>, relations: list<array{from: string, to: string, type: string}>} $dataset */
        $dataset = require __DIR__.'/Data/FootwearConstructionDataset.php';
        $concepts = $dataset['concepts'];
        $relations = $dataset['relations'];
        $this->assertDatasetSemanticIntegrity($concepts, $relations);

        $languages = Language::query()->get()->keyBy('code');
        $domainsBySlug = Domain::query()->get()->keyBy('slug');

        $usedSlugs = [];

        DB::transaction(function () use ($concepts, $relations, $languages, $domainsBySlug, &$usedSlugs): void {
            ConceptRelation::query()->delete();
            Concept::query()->delete();

            $conceptIdByKey = [];

            foreach ($concepts as $row) {
                $key = $row['key'];
                $concept = Concept::query()->create([
                    'status' => $row['status'] ?? WorkflowStatus::PUBLISHED,
                    'difficulty_level' => $row['difficulty_level'] ?? 'intermediate',
                    'is_featured' => (bool) ($row['featured'] ?? false),
                ]);
                $conceptIdByKey[$key] = $concept->id;

                foreach ($row['domains'] ?? [] as $slug) {
                    $domain = $domainsBySlug->get($slug);
                    if ($domain !== null) {
                        $concept->domains()->attach($domain->id);
                    }
                }

                foreach ($row['translations'] as $langCode => $tr) {
                    $language = $languages->get($langCode);
                    if ($language === null) {
                        continue;
                    }
                    $baseSlug = $tr['slug'] ?? Str::slug($tr['term']);
                    $slug = $this->uniqueSlug($baseSlug, (int) $language->id, $usedSlugs);

                    $translation = ConceptTranslation::query()->create([
                        'concept_id' => $concept->id,
                        'language_id' => $language->id,
                        'status' => $tr['status'] ?? ($row['translation_status'] ?? WorkflowStatus::PUBLISHED),
                        'terminology_status' => $tr['terminology_status'] ?? ($row['terminology_status'] ?? \App\Support\Editorial\TerminologyStatus::DRAFT),
                        'validated_at' => $tr['validated_at'] ?? ($row['validated_at'] ?? null),
                        'validated_by' => $tr['validated_by'] ?? ($row['validated_by'] ?? null),
                        'term' => $tr['term'],
                        'slug' => $slug,
                        'short_definition' => $tr['short_definition'] ?? null,
                        'full_definition' => $tr['full_definition'] ?? null,
                        'seo_title' => $tr['seo_title'] ?? ($tr['term'].' | LexiCraft'),
                        'seo_description' => $tr['seo_description'] ?? ($tr['short_definition'] ?? ''),
                        'meta_keywords' => $tr['meta_keywords'] ?? null,
                        'industry_notes' => $tr['industry_notes'] ?? null,
                        'editorial_notes' => $tr['editorial_notes'] ?? ($row['editorial_notes'] ?? null),
                        'source_reference_text' => $tr['source_reference_text'] ?? ($row['source_reference_text'] ?? null),
                    ]);

                    $order = 0;
                    foreach ($tr['examples'] ?? [] as $ex) {
                        Example::query()->create([
                            'concept_translation_id' => $translation->id,
                            'example' => $ex['example'],
                            'context' => $ex['context'] ?? null,
                            'sort_order' => $ex['sort_order'] ?? $order++,
                        ]);
                    }
                }
            }

            foreach ($relations as $rel) {
                $fromId = $conceptIdByKey[$rel['from']] ?? null;
                $toId = $conceptIdByKey[$rel['to']] ?? null;
                if ($fromId === null || $toId === null || $fromId === $toId) {
                    continue;
                }
                if (! SemanticGraph::isAllowedStoredType($rel['type'])) {
                    continue;
                }
                ConceptRelation::query()->firstOrCreate(
                    [
                        'concept_id' => $fromId,
                        'related_concept_id' => $toId,
                        'relation_type' => $rel['type'],
                    ],
                );
            }
        });
    }

    /**
     * @param  list<array<string, mixed>>  $concepts
     * @param  list<array{from: string, to: string, type: string}>  $relations
     */
    private function assertDatasetSemanticIntegrity(array $concepts, array $relations): void
    {
        $conceptKeys = collect($concepts)
            ->pluck('key')
            ->filter(fn (mixed $k): bool => is_string($k) && $k !== '')
            ->values()
            ->all();
        $known = array_fill_keys($conceptKeys, true);

        $missingTargets = [];
        $invalidTypes = [];
        $selfRelations = [];
        $seen = [];
        $duplicateTuples = [];
        $contradictions = [];

        foreach ($relations as $rel) {
            $from = $rel['from'] ?? '';
            $to = $rel['to'] ?? '';
            $type = $rel['type'] ?? '';

            if (! isset($known[$from])) {
                $missingTargets[] = "missing_from:{$from}";
            }
            if (! isset($known[$to])) {
                $missingTargets[] = "missing_to:{$to}";
            }
            if (! SemanticGraph::isAllowedStoredType($type)) {
                $invalidTypes[] = "{$from}->{$to}:{$type}";
            }
            if ($from === $to) {
                $selfRelations[] = "{$from}:{$type}";
            }

            $tuple = "{$from}|{$to}|{$type}";
            if (isset($seen[$tuple])) {
                $duplicateTuples[] = $tuple;
            }
            $seen[$tuple] = true;

            if ($type === 'broader' && isset($seen["{$from}|{$to}|narrower"])) {
                $contradictions[] = "{$from}<->{$to}";
            }
            if ($type === 'narrower' && isset($seen["{$from}|{$to}|broader"])) {
                $contradictions[] = "{$from}<->{$to}";
            }
        }

        $issues = array_merge(
            array_unique($missingTargets),
            array_unique($invalidTypes),
            array_unique($selfRelations),
            array_unique($duplicateTuples),
            array_unique($contradictions),
        );

        if ($issues !== []) {
            throw new \RuntimeException('Footwear dataset semantic-integrity failure: '.implode(', ', $issues));
        }
    }

    private function uniqueSlug(string $base, int $languageId, array &$usedSlugs): string
    {
        $base = $base !== '' ? $base : 'concept';
        $slug = $base;
        $n = 2;
        while (isset($usedSlugs[$languageId][$slug])) {
            $slug = $base.'-'.$n;
            $n++;
        }
        $usedSlugs[$languageId][$slug] = true;

        return $slug;
    }
}
