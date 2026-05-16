<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConceptTranslationRequest;
use App\Http\Requests\Admin\UpdateConceptTranslationRequest;
use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Example;
use App\Models\Language;
use App\Support\Editorial\DuplicateDetectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

final class ConceptTranslationController extends Controller
{
    public function store(StoreConceptTranslationRequest $request, Concept $concept): RedirectResponse
    {
        $this->authorize('manageTranslations', $concept);

        $validated = $request->validated();

        $language = Language::query()->findOrFail($validated['language_id']);
        if (! $language->is_active) {
            return back()->withErrors(['language_id' => __('admin.msg_choose_active_language')])->withInput();
        }

        if ($concept->translations()->where('language_id', $validated['language_id'])->exists()) {
            return back()->withErrors(['language_id' => __('admin.msg_locale_exists')])->withInput();
        }

        $duplicate = DuplicateDetectionService::findExactTermDuplicate(
            (int) $validated['language_id'],
            (string) $validated['term'],
            ignoreConceptId: $concept->id
        );
        if ($duplicate !== null) {
            return back()->withErrors([
                'term' => __('admin.msg_duplicate_term_language', ['id' => $duplicate->concept_id]),
            ])->withInput();
        }

        $translation = ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $validated['language_id'],
            'status' => $validated['status'],
            'term' => $validated['term'],
            'slug' => $validated['slug'],
            'short_definition' => $validated['short_definition'] ?? null,
            'full_definition' => $validated['full_definition'] ?? null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'seo_canonical_url' => null,
            'og_title' => null,
            'og_description' => null,
        ]);

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('admin.translation_added'));
    }

    public function update(UpdateConceptTranslationRequest $request, Concept $concept, ConceptTranslation $translation): RedirectResponse
    {
        $this->authorize('manageTranslations', $concept);
        abort_unless($translation->concept_id === $concept->id, 404);

        $validated = $request->validated();

        $duplicate = DuplicateDetectionService::findExactTermDuplicate(
            $translation->language_id,
            (string) $validated['term'],
            ignoreTranslationId: $translation->id,
            ignoreConceptId: $concept->id,
        );
        if ($duplicate !== null) {
            return back()->withErrors([
                'term' => __('admin.msg_duplicate_term_language', ['id' => $duplicate->concept_id]),
            ])->withInput();
        }

        DB::transaction(function () use ($validated, $request, $translation): void {
            $translation->update([
                'status' => $validated['status'],
                'term' => $validated['term'],
                'slug' => $validated['slug'],
                'short_definition' => $validated['short_definition'] ?? null,
                'full_definition' => $validated['full_definition'] ?? null,
                'seo_title' => $validated['seo_title'] ?? null,
                'seo_description' => $validated['seo_description'] ?? null,
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'industry_notes' => $validated['industry_notes'] ?? null,
                'seo_canonical_url' => $validated['seo_canonical_url'] ?? null,
                'og_title' => $validated['og_title'] ?? null,
                'og_description' => $validated['og_description'] ?? null,
            ]);

            $rows = $request->input('examples', []);
            if (is_array($rows)) {
                $keptIds = [];
                foreach ($rows as $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $exampleText = trim((string) ($row['example'] ?? ''));
                    if ($exampleText === '') {
                        continue;
                    }

                    $id = isset($row['id']) ? (int) $row['id'] : 0;
                    $sort = isset($row['sort_order']) ? (int) $row['sort_order'] : 0;
                    $context = isset($row['context']) ? trim((string) $row['context']) : null;
                    $context = $context === '' ? null : $context;

                    if ($id > 0) {
                        $example = Example::query()
                            ->whereKey($id)
                            ->where('concept_translation_id', $translation->id)
                            ->first();
                        if ($example !== null) {
                            $example->update([
                                'example' => $exampleText,
                                'context' => $context,
                                'sort_order' => $sort,
                            ]);
                            $keptIds[] = $example->id;
                        }
                    } else {
                        $created = Example::query()->create([
                            'concept_translation_id' => $translation->id,
                            'example' => $exampleText,
                            'context' => $context,
                            'sort_order' => $sort,
                        ]);
                        $keptIds[] = $created->id;
                    }
                }

                $toDelete = Example::query()->where('concept_translation_id', $translation->id);
                if (count($keptIds) > 0) {
                    $toDelete->whereNotIn('id', $keptIds)->delete();
                } else {
                    $toDelete->delete();
                }
            }
        });

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('admin.translation_saved'));
    }
}
