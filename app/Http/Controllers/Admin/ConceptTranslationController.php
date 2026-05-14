<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Example;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

final class ConceptTranslationController extends Controller
{
    public function store(Request $request, Concept $concept): RedirectResponse
    {
        $validated = $request->validate([
            'language_id' => ['required', 'exists:languages,id'],
            'term' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('concept_translations', 'slug')->where(
                    fn ($q) => $q->where('language_id', (int) $request->integer('language_id')),
                ),
            ],
            'short_definition' => ['nullable', 'string'],
            'full_definition' => ['nullable', 'string'],
        ]);

        $language = Language::query()->findOrFail($validated['language_id']);
        if (! $language->is_active) {
            return back()->withErrors(['language_id' => __('Choose an active language.')])->withInput();
        }

        if ($concept->translations()->where('language_id', $validated['language_id'])->exists()) {
            return back()->withErrors(['language_id' => __('This concept already has that locale.')])->withInput();
        }

        $translation = ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $validated['language_id'],
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

        $translation->searchable();

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('Translation added.'));
    }

    public function update(Request $request, Concept $concept, ConceptTranslation $translation): RedirectResponse
    {
        abort_unless($translation->concept_id === $concept->id, 404);

        $validated = $request->validate([
            'term' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('concept_translations', 'slug')
                    ->where(fn ($q) => $q->where('language_id', $translation->language_id))
                    ->ignore($translation->id),
            ],
            'short_definition' => ['nullable', 'string'],
            'full_definition' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'industry_notes' => ['nullable', 'string'],
            'seo_canonical_url' => ['nullable', 'string', 'max:2048', 'url'],
            'og_title' => ['nullable', 'string', 'max:512'],
            'og_description' => ['nullable', 'string'],
            'examples' => ['nullable', 'array'],
            'examples.*.id' => ['nullable', 'integer'],
            'examples.*.example' => ['nullable', 'string'],
            'examples.*.context' => ['nullable', 'string', 'max:64'],
            'examples.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        DB::transaction(function () use ($validated, $request, $translation): void {
            $translation->update([
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

        $translation->searchable();

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('Translation saved.'));
    }
}
