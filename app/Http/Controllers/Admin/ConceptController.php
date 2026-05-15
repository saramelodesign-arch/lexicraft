<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConceptRequest;
use App\Http\Requests\Admin\UpdateConceptRequest;
use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\Language;
use App\Support\Editorial\DuplicateDetectionService;
use App\Support\Editorial\SemanticRelationGuard;
use App\Support\Editorial\WorkflowStatus;
use App\Support\SemanticGraph;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class ConceptController extends Controller
{
    public function index(Request $request): View
    {
        $languages = Language::query()->where('is_active', true)->orderBy('code')->get();
        $domains = Domain::query()->where('is_active', true)->orderBy('slug')->get(['id', 'slug']);

        $query = Concept::query()
            ->with(['translations' => fn ($q) => $q->with('language')->orderBy('language_id')])
            ->withCount('translations')
            ->withCount(['translations as published_translations_count' => fn ($q) => $q->where('status', WorkflowStatus::PUBLISHED)]);

        $status = $request->string('status')->toString();
        if ($status !== '' && WorkflowStatus::isValid($status)) {
            $query->where('status', $status);
        }

        $domainId = $request->integer('domain_id');
        if ($domainId > 0) {
            $query->whereHas('domains', fn ($q) => $q->where('domains.id', $domainId));
        }

        $missingLocale = $request->string('missing_locale')->toString();
        if ($missingLocale !== '' && Language::activeIdForCode($missingLocale) !== null) {
            $languageId = Language::activeIdForCode($missingLocale);
            $query->whereDoesntHave('translations', fn ($q) => $q->where('language_id', $languageId));
        }

        $needle = trim($request->string('q')->toString());
        if ($needle !== '') {
            $like = '%'.addcslashes($needle, '%_\\').'%';
            $query->whereHas('translations', function ($q) use ($like): void {
                $q->where('term', 'like', $like)
                    ->orWhere('slug', 'like', $like)
                    ->orWhere('short_definition', 'like', $like);
            });
        }

        $concepts = $query->latest('updated_at')->paginate(30)->withQueryString();

        return view('admin.concepts.index', [
            'concepts' => $concepts,
            'languages' => $languages,
            'domains' => $domains,
            'filters' => [
                'q' => $needle,
                'status' => $status,
                'domain_id' => $domainId > 0 ? $domainId : null,
                'missing_locale' => $missingLocale,
            ],
        ]);
    }

    public function create(): View
    {
        $languages = Language::query()->where('is_active', true)->orderBy('code')->get();
        $domains = Domain::query()->where('is_active', true)->orderBy('slug')->get();

        return view('admin.concepts.create', [
            'languages' => $languages,
            'domains' => $domains,
        ]);
    }

    public function store(StoreConceptRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $language = Language::query()->findOrFail($validated['language_id']);
        if (! $language->is_active) {
            return back()->withErrors(['language_id' => __('Choose an active language.')])->withInput();
        }

        $duplicate = DuplicateDetectionService::findExactTermDuplicate(
            (int) $validated['language_id'],
            (string) $validated['term'],
        );
        if ($duplicate !== null) {
            return back()->withErrors([
                'term' => __('A concept translation with this term already exists in the selected language (concept #:id).', ['id' => $duplicate->concept_id]),
            ])->withInput();
        }

        $concept = DB::transaction(function () use ($validated, $request): Concept {
            $concept = Concept::query()->create([
                'status' => $validated['status'],
                'difficulty_level' => $validated['difficulty_level'] ?? null,
                'is_featured' => $request->boolean('is_featured'),
            ]);

            $concept->domains()->sync($validated['domain_ids'] ?? []);

            ConceptTranslation::query()->create([
                'concept_id' => $concept->id,
                'language_id' => $validated['language_id'],
                'status' => $validated['translation_status'] ?? $validated['status'],
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

            return $concept;
        });

        $concept->translations()->first()?->searchable();

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('Concept created.'));
    }

    public function edit(Concept $concept): View
    {
        $concept->load([
            'translations' => fn ($q) => $q->with(['language', 'examples' => fn ($eq) => $eq->orderBy('sort_order')]),
            'domains:id',
            'outgoingRelations' => fn ($q) => $q->with([
                'relatedConcept' => fn ($rq) => $rq->with(['translations' => fn ($tq) => $tq->with('language')]),
            ]),
            'media',
        ]);

        $languages = Language::query()->where('is_active', true)->orderBy('code')->get();
        $domains = Domain::query()->where('is_active', true)->orderBy('slug')->get();
        $relationTypes = collect(SemanticGraph::STORED_TYPES)
            ->reject(fn (string $t): bool => $t === 'see_also')
            ->values()
            ->all();

        return view('admin.concepts.edit', [
            'concept' => $concept,
            'languages' => $languages,
            'domains' => $domains,
            'relationTypes' => $relationTypes,
            'workflowStates' => WorkflowStatus::all(),
            'semanticWarnings' => SemanticRelationGuard::semanticWarningsForConcept($concept),
            'translationDuplicateWarnings' => $concept->translations
                ->mapWithKeys(fn (ConceptTranslation $translation) => [
                    $translation->id => DuplicateDetectionService::findNearDuplicates(
                        $translation->language_id,
                        $translation->term,
                        $concept->id
                    )->take(5),
                ]),
        ]);
    }

    public function update(UpdateConceptRequest $request, Concept $concept): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $concept): void {
            $concept->update([
                'status' => $validated['status'],
                'difficulty_level' => $validated['difficulty_level'] ?? null,
                'is_featured' => $request->boolean('is_featured'),
            ]);
            $concept->domains()->sync($validated['domain_ids'] ?? []);
        });

        $concept->translations()->each->searchable();

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('Concept updated.'));
    }

    public function destroy(Concept $concept): RedirectResponse
    {
        $concept->delete();

        return redirect()
            ->route('admin.concepts.index')
            ->with('status', __('Concept deleted.'));
    }
}
