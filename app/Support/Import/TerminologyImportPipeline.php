<?php

namespace App\Support\Import;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\Language;
use App\Support\Editorial\DuplicateDetectionService;
use App\Support\Editorial\EditorialQualityGuard;
use App\Support\Editorial\SlugGovernance;
use App\Support\Editorial\TerminologyStatus;
use App\Support\Editorial\WorkflowStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class TerminologyImportPipeline
{
    /**
     * @param  iterable<TerminologyImportRow>  $rows
     * @return array{
     *     processed: int,
     *     created_concepts: int,
     *     created_translations: int,
     *     updated_translations: int,
     *     skipped: int,
     *     warnings: list<string>,
     *     warned: int,
     *     errors: list<string>
     * }
     */
    public function import(iterable $rows, bool $dryRun = true): array
    {
        $summary = [
            'processed' => 0,
            'created_concepts' => 0,
            'created_translations' => 0,
            'updated_translations' => 0,
            'skipped' => 0,
            'warnings' => [],
            'warned' => 0,
            'errors' => [],
        ];
        /** @var array<string, array<string, string>> $batchSlugMap */
        $batchSlugMap = [];

        foreach ($rows as $index => $row) {
            $summary['processed']++;

            try {
                $this->validateBatchSlugMapping($row, $batchSlugMap);
                $this->validateRow($row);
                $warnings = $this->warningsForRow($row);
                foreach ($warnings as $warning) {
                    $summary['warnings'][] = 'Row '.($index + 1).': '.$warning;
                }
                if ($warnings !== []) {
                    $summary['warned'] += 1;
                }
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
                'concept_status' => __('admin.msg_invalid_concept_workflow', ['status' => $row->conceptStatus]),
            ]);
        }

        if (! WorkflowStatus::isValid($row->translationStatus)) {
            throw ValidationException::withMessages([
                'translation_status' => __('admin.msg_invalid_translation_workflow', ['status' => $row->translationStatus]),
            ]);
        }

        if ($row->terminologyStatus !== null) {
            if (! TerminologyStatus::isValid($row->terminologyStatus)) {
                throw ValidationException::withMessages([
                    'terminology_status' => 'Invalid terminology status for import row.',
                ]);
            }

            if (! TerminologyStatus::isWorkflowCoherent($row->terminologyStatus, $row->translationStatus)) {
                throw ValidationException::withMessages([
                    'terminology_status' => 'Terminology status is not coherent with translation workflow state.',
                ]);
            }
        }

        if (! SlugGovernance::isSeoSafe($row->slug)) {
            throw ValidationException::withMessages([
                'slug' => __('admin.msg_slug_not_seo_safe'),
            ]);
        }

        $languageId = Language::activeIdForCode($row->locale);
        if ($languageId === null) {
            throw ValidationException::withMessages([
                'locale' => __('admin.msg_unknown_or_inactive_locale_with_value', ['locale' => $row->locale]),
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
                    'domains' => __('admin.msg_unknown_domain_slug', ['slug' => $slug]),
                ]);
            }
        }

        $duplicate = DuplicateDetectionService::findExactTermDuplicate($languageId, $row->term);
        if ($duplicate !== null && $duplicate->slug !== $row->slug) {
            throw ValidationException::withMessages([
                'term' => __('admin.msg_exact_duplicate_term', [
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
            throw ValidationException::withMessages(['locale' => __('admin.msg_locale_inactive')]);
        }

        $translation = ConceptTranslation::query()
            ->where('language_id', $languageId)
            ->where('slug', $row->slug)
            ->first();

        if ($translation !== null) {
            $translation->update([
                'status' => $row->translationStatus,
                'terminology_status' => $row->terminologyStatus ?? $translation->terminology_status,
                'term' => $row->term,
                'short_definition' => $row->shortDefinition,
                'full_definition' => $row->fullDefinition,
            ]);

            $concept = $translation->concept;
            if ($concept !== null) {
                $concept->update([
                    'status' => $row->conceptStatus,
                ]);
            }

            if ($concept !== null) {
                $this->syncDomains($concept, $row->domains);
            }

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
            'terminology_status' => $row->terminologyStatus ?? TerminologyStatus::DRAFT,
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

    /**
     * @param  array<string, array<string, string>>  $batchSlugMap
     */
    private function validateBatchSlugMapping(TerminologyImportRow $row, array &$batchSlugMap): void
    {
        $locale = mb_strtolower(trim($row->locale), 'UTF-8');
        $slug = mb_strtolower(trim($row->slug), 'UTF-8');
        if ($locale === '' || $slug === '') {
            return;
        }

        $term = DuplicateDetectionService::normalizeTerm($row->term);
        $known = $batchSlugMap[$locale][$slug] ?? null;
        if ($known !== null && $known !== $term) {
            throw ValidationException::withMessages([
                'slug' => 'Conflicting locale slug mapping detected within import batch.',
            ]);
        }

        $batchSlugMap[$locale][$slug] = $term;
    }

    /**
     * @return list<string>
     */
    private function warningsForRow(TerminologyImportRow $row): array
    {
        $warnings = [];
        $languageId = Language::activeIdForCode($row->locale);
        if ($languageId === null) {
            return $warnings;
        }

        $nearDuplicates = DuplicateDetectionService::findNearDuplicates($languageId, $row->term)->take(3);
        if ($nearDuplicates->isNotEmpty()) {
            $hits = $nearDuplicates
                ->map(fn (ConceptTranslation $translation): string => sprintf('%s (#%d)', $translation->term, $translation->concept_id))
                ->implode(', ');
            $warnings[] = 'Potential near-duplicate terminology: '.$hits;
        }

        return $warnings;
    }
}
