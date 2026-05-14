<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\DomainTranslation;
use App\Models\Language;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

final class DomainController extends Controller
{
    public function index(Request $request): View
    {
        $needle = trim($request->string('q')->toString());

        $query = Domain::query()
            ->with(['parent', 'translations' => fn ($q) => $q->with('language')])
            ->withCount('concepts');

        if ($needle !== '') {
            $like = '%'.addcslashes($needle, '%_\\').'%';
            $query->where(function ($w) use ($like): void {
                $w->where('slug', 'like', $like)
                    ->orWhereHas('translations', fn ($t) => $t->where('name', 'like', $like));
            });
        }

        $domains = $query->orderBy('sort_order')->orderBy('slug')->paginate(40)->withQueryString();

        return view('admin.domains.index', [
            'domains' => $domains,
            'filters' => ['q' => $needle],
        ]);
    }

    public function create(): View
    {
        $languages = Language::query()->where('is_active', true)->orderBy('code')->get();
        $parents = Domain::query()->where('is_active', true)->orderBy('slug')->get();

        return view('admin.domains.create', [
            'languages' => $languages,
            'parents' => $parents,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $languages = Language::query()->where('is_active', true)->orderBy('code')->get();
        $rules = $this->translationRules($languages, null);
        $rules['slug'] = ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:domains,slug'];
        $rules['parent_id'] = ['nullable', 'exists:domains,id'];
        $rules['icon'] = ['nullable', 'string', 'max:255'];
        $rules['sort_order'] = ['nullable', 'integer', 'min:0', 'max:65535'];
        $rules['is_active'] = ['sometimes', 'boolean'];

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $languages, $request): void {
            $domain = Domain::query()->create([
                'parent_id' => $validated['parent_id'] ?? null,
                'slug' => $validated['slug'],
                'icon' => $validated['icon'] ?? null,
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]);

            foreach ($languages as $language) {
                $code = $language->code;
                $row = $validated['translations'][$code] ?? null;
                if (! is_array($row)) {
                    continue;
                }
                DomainTranslation::query()->create([
                    'domain_id' => $domain->id,
                    'language_id' => $language->id,
                    'name' => $row['name'],
                    'slug' => $row['slug'],
                    'description' => $row['description'] ?? null,
                ]);
            }
        });

        $domain = Domain::query()->where('slug', $validated['slug'])->firstOrFail();

        return redirect()
            ->route('admin.domains.edit', $domain)
            ->with('status', __('Domain created.'));
    }

    public function edit(Domain $domain): View
    {
        $domain->load(['translations.language', 'parent']);
        $languages = Language::query()->where('is_active', true)->orderBy('code')->get();
        $parents = Domain::query()->where('is_active', true)->where('id', '!=', $domain->id)->orderBy('slug')->get();

        return view('admin.domains.edit', [
            'domain' => $domain,
            'languages' => $languages,
            'parents' => $parents,
        ]);
    }

    public function update(Request $request, Domain $domain): RedirectResponse
    {
        $languages = Language::query()->where('is_active', true)->orderBy('code')->get();
        $rules = $this->translationRules($languages, $domain);
        $rules['slug'] = [
            'required',
            'string',
            'max:255',
            'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            Rule::unique('domains', 'slug')->ignore($domain->id),
        ];
        $rules['parent_id'] = ['nullable', 'exists:domains,id', Rule::notIn([$domain->id])];
        $rules['icon'] = ['nullable', 'string', 'max:255'];
        $rules['sort_order'] = ['nullable', 'integer', 'min:0', 'max:65535'];
        $rules['is_active'] = ['sometimes', 'boolean'];

        $validated = $request->validate($rules);

        if (! empty($validated['parent_id'])) {
            $this->assertNoHierarchyCycle((int) $validated['parent_id'], $domain);
        }

        DB::transaction(function () use ($validated, $languages, $request, $domain): void {
            $domain->update([
                'parent_id' => $validated['parent_id'] ?? null,
                'slug' => $validated['slug'],
                'icon' => $validated['icon'] ?? null,
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]);

            foreach ($languages as $language) {
                $code = $language->code;
                $row = $validated['translations'][$code] ?? null;
                if (! is_array($row)) {
                    continue;
                }
                DomainTranslation::query()->updateOrCreate(
                    [
                        'domain_id' => $domain->id,
                        'language_id' => $language->id,
                    ],
                    [
                        'name' => $row['name'],
                        'slug' => $row['slug'],
                        'description' => $row['description'] ?? null,
                    ],
                );
            }
        });

        return redirect()
            ->route('admin.domains.edit', $domain)
            ->with('status', __('Domain updated.'));
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        if ($domain->children()->exists()) {
            return back()->withErrors(['domain' => __('Reassign or delete child domains first.')]);
        }

        $domain->delete();

        return redirect()
            ->route('admin.domains.index')
            ->with('status', __('Domain deleted.'));
    }

    /**
     * @param  Collection<int, Language>  $languages
     * @return array<string, mixed>
     */
    private function translationRules($languages, ?Domain $domain): array
    {
        $rules = ['translations' => ['required', 'array']];

        foreach ($languages as $language) {
            $code = $language->code;
            $translationId = $domain !== null
                ? DomainTranslation::query()
                    ->where('domain_id', $domain->id)
                    ->where('language_id', $language->id)
                    ->value('id')
                : null;

            $rules["translations.{$code}"] = ['required', 'array'];
            $rules["translations.{$code}.name"] = ['required', 'string', 'max:255'];
            $rules["translations.{$code}.slug"] = [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('domain_translations', 'slug')
                    ->where(fn ($q) => $q->where('language_id', $language->id))
                    ->ignore($translationId),
            ];
            $rules["translations.{$code}.description"] = ['nullable', 'string'];
        }

        return $rules;
    }

    private function assertNoHierarchyCycle(int $parentId, Domain $domain): void
    {
        $walker = $parentId;
        for ($i = 0; $i < 64; $i++) {
            if ($walker === (int) $domain->id) {
                abort(422, __('Invalid parent: would create a cycle.'));
            }
            $next = Domain::query()->whereKey($walker)->value('parent_id');
            if ($next === null) {
                return;
            }
            $walker = (int) $next;
        }
    }
}
