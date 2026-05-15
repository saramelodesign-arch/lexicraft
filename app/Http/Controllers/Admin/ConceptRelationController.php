<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConceptRelationRequest;
use App\Models\Concept;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Support\Editorial\SemanticRelationGuard;
use App\Support\SemanticGraph;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;

final class ConceptRelationController extends Controller
{
    public function store(StoreConceptRelationRequest $request, Concept $concept): RedirectResponse
    {
        $validated = $request->validated();

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
            SemanticRelationGuard::assertCanCreate((int) $concept->id, $relatedConceptId, $validated['relation_type']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            ConceptRelation::query()->create([
                'concept_id' => $concept->id,
                'related_concept_id' => $relatedConceptId,
                'relation_type' => $validated['relation_type'],
            ]);
            if ($validated['relation_type'] === 'synonym') {
                SemanticRelationGuard::ensureSynonymIsSymmetric((int) $concept->id, $relatedConceptId);
            }
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
