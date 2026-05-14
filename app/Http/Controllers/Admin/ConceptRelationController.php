<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Support\Locales;
use App\Support\SemanticGraph;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class ConceptRelationController extends Controller
{
    public function store(Request $request, Concept $concept): RedirectResponse
    {
        $validated = $request->validate([
            'relation_type' => ['required', 'string', Rule::in(SemanticGraph::STORED_TYPES)],
            'related_locale' => ['required', 'string', Rule::in(Locales::codes())],
            'related_slug' => ['required', 'string', 'max:255'],
        ]);

        SemanticGraph::assertAllowedStoredType($validated['relation_type']);

        $languageId = Language::activeIdForCode($validated['related_locale']);
        if ($languageId === null) {
            return back()->withErrors(['related_locale' => __('Unknown or inactive locale.')])->withInput();
        }

        $relatedTranslation = ConceptTranslation::query()
            ->where('slug', mb_strtolower($validated['related_slug'], 'UTF-8'))
            ->where('language_id', $languageId)
            ->whereHas('concept', fn ($q) => $q->where('status', '!=', 'archived'))
            ->first();

        if ($relatedTranslation === null) {
            return back()->withErrors(['related_slug' => __('No concept found for that slug in the chosen locale.')])->withInput();
        }

        $relatedConceptId = (int) $relatedTranslation->concept_id;

        if ($relatedConceptId === (int) $concept->id) {
            return back()->withErrors(['related_slug' => __('A concept cannot relate to itself.')])->withInput();
        }

        try {
            ConceptRelation::query()->create([
                'concept_id' => $concept->id,
                'related_concept_id' => $relatedConceptId,
                'relation_type' => $validated['relation_type'],
            ]);
        } catch (QueryException) {
            return back()->withErrors(['relation_type' => __('That relation already exists.')])->withInput();
        }

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('Relation added.'));
    }

    public function destroy(Concept $concept, ConceptRelation $relation): RedirectResponse
    {
        abort_unless($relation->concept_id === $concept->id, 404);

        $relation->delete();

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('Relation removed.'));
    }
}
