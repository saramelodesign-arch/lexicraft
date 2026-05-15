<?php

namespace App\Support\Import;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\Language;
use App\Support\Editorial\DuplicateDetectionService;
use App\Support\Editorial\EditorialQualityGuard;
use App\Support\Editorial\SlugGovernance;
use App\Support\Editorial\WorkflowStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class TerminologyImportPipeline
{
    /**
     * @param  iterable<TerminologyImportRow>  $rows
     * @return array{processed: int, created_concepts: int, created_translations: int, updated_translations: int, skipped: int, errors: list<string>}
     */
    public function import(iterable $rows, bool $dryRun = true): array
    {
        $summary = [
            'processed' => 0,
            'created_concepts' => 0,
            'created_translations' => 0,
            'updated_translations' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        foreach ($rows as $index => $row) {
            $summary['processed']++;

            try {
                $this->validateRow($row);
                if ($dryRun) {
                    continue;
                }

                $result = DB::transaction(fn () => $this->upsertRow($row));
                $summary[$result]++;
            } catch (\Throwable $e) {
                $summary['skipped']++;
                $summary['errors'][] = 'Row '.($index + 1).': '.$e->getMessage();
            }
        }

        return $summary;
    }

    /**
     * @throws ValidationException
     */
    public function validateRow(TerminologyImportRow $row): void
    {
        if (! WorkflowStatus::isValid($row->conceptStatus)) {
            throw ValidationException::withMessages([
                'concept_status' => __('Invalid concept workflow status: :status', ['status' => $row->conceptStatus]),
            ]);
        }

        if (! WorkflowStatus::isValid($row->translationStatus)) {
            throw ValidationException::withMessages([
                'translation_status' => __('Invalid translation workflow status: :status', ['status' => $row->translationStatus]),
            ]);
        }

        if (! SlugGovernance::isSeoSafe($row->slug)) {
            throw ValidationException::withMessages([
                'slug' => __('Slug is not SEO-safe or exceeds length limits.'),
            ]);
        }

        $languageId = Language::activeIdForCode($row->locale);
        if ($languageId === null) {
            throw ValidationException::withMessages([
                'locale' => __('Unknown or inactive locale: :locale', ['locale' => $row->locale]),
            ]);
        }

        EditorialQualityGuard::assertTranslationQuality(
            $row->term,
            $row->shortDefinition,
            $row->fullDefinition,
            [],
            $row->translationStatus
        );

        foreach ($row->domains as $slug) {
            $exists = Domain::query()->where('slug', $slug)->where('is_active', true)->exists();
            if (! $exists) {
                throw ValidationException::withMessages([
                    'domains' => __('Unknown or inactive domain slug: :slug', ['slug' => $slug]),
                ]);
            }
        }

        $duplicate = DuplicateDetectionService::findExactTermDuplicate($languageId, $row->term);
        if ($duplicate !== null && $duplicate->slug !== $row->slug) {
            throw ValidationException::withMessages([
                'term' => __('Exact duplicate term detected in locale :locale (concept #:id).', [
                    'locale' => strtoupper($row->locale),
                    'id' => $duplicate->concept_id,
                ]),
            ]);
        }
    }

    /**
     * @return 'created_concepts'|'created_translations'|'updated_translations'
     */
    private function upsertRow(TerminologyImportRow $row): string
    {
        $languageId = Language::activeIdForCode($row->locale);
        if ($languageId === null) {
            throw ValidationException::withMessages(['locale' => __('Locale is inactive.')]);
        }

        $translation = ConceptTranslation::query()
            ->where('language_id', $languageId)
            ->where('slug', $row->slug)
            ->first();

        if ($translation !== null) {
            $translation->update([
                'status' => $row->translationStatus,
                'term' => $row->term,
                'short_definition' => $row->shortDefinition,
                'full_definition' => $row->fullDefinition,
            ]);

            $translation->concept()->update([
                'status' => $row->conceptStatus,
            ]);

            $this->syncDomains($translation->concept, $row->domains);

            return 'updated_translations';
        }

        $concept = Concept::query()->create([
            'status' => $row->conceptStatus,
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $languageId,
            'status' => $row->translationStatus,
            'term' => $row->term,
            'slug' => $row->slug,
            'short_definition' => $row->shortDefinition,
            'full_definition' => $row->fullDefinition,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'seo_canonical_url' => null,
            'og_title' => null,
            'og_description' => null,
        ]);

        $this->syncDomains($concept, $row->domains);

        return 'created_concepts';
    }

    /**
     * @param  list<string>  $domainSlugs
     */
    private function syncDomains(Concept $concept, array $domainSlugs): void
    {
        if ($domainSlugs === []) {
            return;
        }

        $domainIds = Domain::query()
            ->whereIn('slug', $domainSlugs)
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        $concept->domains()->sync($domainIds);
    }
}
