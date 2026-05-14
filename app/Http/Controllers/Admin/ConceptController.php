<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\Language;
use App\Support\SemanticGraph;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

final class ConceptController extends Controller
{
    public function index(Request $request): View
    {
        $languages = Language::query()->where('is_active', true)->orderBy('code')->get();
        $domains = Domain::query()->where('is_active', true)->orderBy('slug')->get(['id', 'slug']);

        $query = Concept::query()
            ->with(['translations' => fn ($q) => $q->with('language')->orderBy('language_id')])
            ->withCount('translations');

        $status = $request->string('status')->toString();
        if ($status !== '' && in_array($status, ['draft', 'published', 'archived'], true)) {
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'difficulty_level' => ['nullable', 'string', 'max:64'],
            'is_featured' => ['sometimes', 'boolean'],
            'domain_ids' => ['nullable', 'array'],
            'domain_ids.*' => ['integer', 'exists:domains,id'],
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
        ]);
    }

    public function update(Request $request, Concept $concept): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'difficulty_level' => ['nullable', 'string', 'max:64'],
            'is_featured' => ['sometimes', 'boolean'],
            'domain_ids' => ['nullable', 'array'],
            'domain_ids.*' => ['integer', 'exists:domains,id'],
        ]);

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
