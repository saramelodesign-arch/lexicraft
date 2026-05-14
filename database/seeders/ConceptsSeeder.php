<?php

namespace Database\Seeders;

use App\Models\Concept;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\Example;
use App\Models\Example;
use App\Models\Language;
use App\Models\Language;
use App\Support\SemanticGraph;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConceptsSeeder extends Seeder
{
    public function run(): void
    {
        $batches = [
            __DIR__.'/Data/IndustrialConceptsBatch1.php',
            __DIR__.'/Data/IndustrialConceptsBatch2.php',
            __DIR__.'/Data/IndustrialConceptsBatch3.php',
        ];

        $concepts = [];
        foreach ($batches as $path) {
            $slice = require $path;
            if (! is_array($slice)) {
                continue;
            }
            $concepts = array_merge($concepts, $slice);
        }

        /** @var list<array{from: string, to: string, type: string}> $relations */
        $relations = require __DIR__.'/Data/IndustrialConceptsRelations.php';

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
                    'status' => 'published',
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
                        'term' => $tr['term'],
                        'slug' => $slug,
                        'short_definition' => $tr['short_definition'] ?? null,
                        'full_definition' => $tr['full_definition'] ?? null,
                        'seo_title' => $tr['seo_title'] ?? ($tr['term'].' | LexiCraft'),
                        'seo_description' => $tr['seo_description'] ?? ($tr['short_definition'] ?? ''),
                        'meta_keywords' => $tr['meta_keywords'] ?? null,
                        'industry_notes' => $tr['industry_notes'] ?? null,
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
