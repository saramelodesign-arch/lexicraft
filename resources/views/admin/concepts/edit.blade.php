@extends('layouts.admin', ['pageTitle' => __('Edit concept #:id', ['id' => $concept->id])])

@section('subhead')
    {{ __('Manage publication state, locale copy, semantic graph, and media for this terminology entry.') }}
@endsection

@section('content')
    @if ($semanticWarnings !== [])
        <flux:card class="p-4">
            <ul class="space-y-1 text-sm text-amber-700 dark:text-amber-300">
                @foreach ($semanticWarnings as $warning)
                    <li>• {{ $warning }}</li>
                @endforeach
            </ul>
        </flux:card>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-3">
        <flux:text class="text-sm text-zinc-500">{{ __('UUID: :u', ['u' => $concept->uuid]) }}</flux:text>
        <div class="flex flex-wrap gap-2">
            @php
                $first = $concept->translations->sortBy('language_id')->first();
                $previewLocale = $first?->language?->code ?? \App\Support\Locales::fallback();
                $previewSlug = $first?->slug;
            @endphp
            @if ($previewSlug && $concept->status === 'published')
                <flux:button size="sm" variant="ghost" :href="route('glossary.concept', ['locale' => $previewLocale, 'slug' => $previewSlug])" target="_blank">
                    {{ __('View live') }}
                </flux:button>
            @endif
            <form method="post" action="{{ route('admin.concepts.destroy', $concept) }}" onsubmit="return confirm(@js(__('Delete this concept and all translations?')))">
                @csrf
                @method('DELETE')
                <flux:button size="sm" variant="danger" type="submit">{{ __('Delete concept') }}</flux:button>
            </form>
        </div>
    </div>

    {{-- Core concept --}}
    <flux:card class="mt-6 p-6">
        <flux:heading size="lg">{{ __('Publication & taxonomy') }}</flux:heading>
        <form method="post" action="{{ route('admin.concepts.update', $concept) }}" class="mt-6 grid max-w-3xl gap-6">
            @csrf
            @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <flux:label>{{ __('Workflow status') }}</flux:label>
                    <select name="status" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                        @foreach ($workflowStates as $st)
                            <option value="{{ $st }}" @selected(old('status', $concept->status) === $st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <flux:input name="difficulty_level" value="{{ old('difficulty_level', $concept->difficulty_level) }}" :label="__('Difficulty level')" />
            </div>
            <input type="hidden" name="is_featured" value="0" />
            <flux:checkbox name="is_featured" value="1" :label="__('Featured concept')" :checked="(bool) old('is_featured', $concept->is_featured)" />
            <div class="space-y-2">
                <flux:label>{{ __('Domains') }}</flux:label>
                <div class="grid gap-2 sm:grid-cols-2">
                    @php $sel = collect(old('domain_ids', $concept->domains->pluck('id')->all())); @endphp
                    @foreach ($domains as $domain)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="domain_ids[]" value="{{ $domain->id }}" class="rounded border-zinc-300 dark:border-zinc-600" @checked($sel->contains($domain->id)) />
                            {{ $domain->slug }}
                        </label>
                    @endforeach
                </div>
            </div>
            <flux:button type="submit" variant="primary">{{ __('Save concept') }}</flux:button>
        </form>
    </flux:card>

    {{-- Semantic relations --}}
    <flux:card class="mt-8 p-6">
        <flux:heading size="lg">{{ __('Semantic relations') }}</flux:heading>
        <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Outgoing edges from this concept. Duplicates and self-links are blocked at validation and database level.') }}</flux:text>

        <form method="post" action="{{ route('admin.concepts.relations.store', $concept) }}" class="mt-6 grid gap-4 md:grid-cols-4">
            @csrf
            <div class="space-y-2 md:col-span-1">
                <flux:label>{{ __('Type') }}</flux:label>
                <select name="relation_type" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    @foreach ($relationTypes as $type)
                        <option value="{{ $type }}">{{ str_replace('_', ' ', $type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2 md:col-span-1">
                <flux:label>{{ __('Target locale') }}</flux:label>
                <select name="related_locale" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    @foreach (\App\Support\Locales::codes() as $code)
                        <option value="{{ $code }}">{{ strtoupper($code) }}</option>
                    @endforeach
                </select>
            </div>
            <flux:input class="md:col-span-2" name="related_slug" value="{{ old('related_slug') }}" :label="__('Target slug')" required placeholder="other-term-slug" />
            <div class="md:col-span-4">
                <flux:button type="submit" variant="primary" size="sm">{{ __('Add relation') }}</flux:button>
            </div>
        </form>

        <ul class="mt-6 divide-y divide-zinc-200 dark:divide-zinc-800">
            @forelse ($concept->outgoingRelations as $rel)
                @php
                    $fallbackLid = \App\Models\Language::activeIdForCode(\App\Support\Locales::fallback());
                    $peer = $rel->relatedConcept?->translations->firstWhere('language_id', $fallbackLid)
                        ?? $rel->relatedConcept?->translations->first();
                @endphp
                <li class="flex flex-wrap items-center justify-between gap-2 py-3 text-sm">
                    <div>
                        <span class="font-medium">{{ $rel->relation_type }}</span>
                        <span class="text-zinc-500">→</span>
                        {{ $peer?->term ?? __('Concept #:id', ['id' => $rel->related_concept_id]) }}
                        <span class="font-mono text-xs text-zinc-400">({{ $peer?->slug }})</span>
                    </div>
                    <form method="post" action="{{ route('admin.concepts.relations.destroy', [$concept, $rel]) }}">
                        @csrf
                        @method('DELETE')
                        <flux:button size="sm" variant="ghost" type="submit">{{ __('Remove') }}</flux:button>
                    </form>
                </li>
            @empty
                <li class="py-4 text-sm text-zinc-500">{{ __('No outgoing relations yet.') }}</li>
            @endforelse
        </ul>
    </flux:card>

    {{-- Media --}}
    <flux:card class="mt-8 p-6">
        <flux:heading size="lg">{{ __('Media library') }}</flux:heading>
        <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Featured and gallery assets drive public OG tags; set locale-specific alt and captions.') }}</flux:text>

        <form method="post" action="{{ route('admin.concepts.media.store', $concept) }}" enctype="multipart/form-data" class="mt-6 grid gap-4 md:grid-cols-2">
            @csrf
            <div class="space-y-2">
                <flux:label>{{ __('Collection') }}</flux:label>
                <select name="collection" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="featured">{{ __('Featured image') }}</option>
                    <option value="gallery">{{ __('Gallery') }}</option>
                    <option value="videos">{{ __('Videos / posters') }}</option>
                    <option value="documents">{{ __('Documents') }}</option>
                </select>
            </div>
            <flux:input type="file" name="file" :label="__('File')" required />
            <flux:input name="embed_url" :label="__('Embed URL (videos)')" placeholder="https://…" class="md:col-span-2" />
            <div class="space-y-2 md:col-span-2">
                <flux:label>{{ __('Gallery kind') }}</flux:label>
                <select name="kind" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="">{{ __('—') }}</option>
                    <option value="photo">{{ __('Photo') }}</option>
                    <option value="diagram">{{ __('Diagram') }}</option>
                </select>
            </div>
            @foreach (\App\Support\Locales::codes() as $code)
                <div class="md:col-span-2 rounded-lg border border-zinc-200 p-4 dark:border-zinc-800">
                    <flux:text class="text-xs font-semibold uppercase text-zinc-500">{{ strtoupper($code) }}</flux:text>
                    <div class="mt-2 grid gap-3 sm:grid-cols-3">
                        <flux:input name="locales[{{ $code }}][title]" :label="__('Title')" />
                        <flux:input name="locales[{{ $code }}][alt]" :label="__('Alt text')" />
                        <flux:input name="locales[{{ $code }}][caption]" :label="__('Caption')" />
                    </div>
                </div>
            @endforeach
            <flux:button type="submit" variant="primary" size="sm">{{ __('Upload') }}</flux:button>
        </form>

        <div class="mt-8 space-y-6">
            @foreach ($concept->media->sortBy('order_column') as $media)
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-800">
                    <div class="flex flex-wrap gap-4">
                        @if (str_starts_with((string) $media->mime_type, 'image/'))
                            <img src="{{ $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl() }}" alt="" class="h-24 w-auto rounded border border-zinc-200 dark:border-zinc-700" />
                        @else
                            <div class="flex h-24 w-32 items-center justify-center rounded bg-zinc-100 text-xs dark:bg-zinc-800">{{ $media->mime_type }}</div>
                        @endif
                        <div class="min-w-[200px] flex-1">
                            <flux:text class="text-xs font-mono text-zinc-500">{{ $media->collection_name }} · #{{ $media->id }}</flux:text>
                            <form method="post" action="{{ route('admin.concepts.media.update', [$concept, $media]) }}" class="mt-3 space-y-3">
                                @csrf
                                @method('PATCH')
                                <flux:input name="name" value="{{ old('name', $media->name) }}" :label="__('File label')" />
                                <flux:input name="embed_url" value="{{ old('embed_url', $media->getCustomProperty('embed_url')) }}" :label="__('Embed URL')" />
                                <div class="space-y-2">
                                    <flux:label>{{ __('Gallery kind') }}</flux:label>
                                    <select name="kind" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                                        @foreach (['', 'photo', 'diagram'] as $k)
                                            <option value="{{ $k }}" @selected(old('kind', $media->getCustomProperty('kind')) === $k)>{{ $k === '' ? '—' : $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach (\App\Support\Locales::codes() as $code)
                                    @php
                                        $loc = $media->getCustomProperty('locales')[$code] ?? [];
                                    @endphp
                                    <div class="rounded border border-zinc-100 p-3 dark:border-zinc-800">
                                        <flux:text class="text-xs font-semibold text-zinc-500">{{ strtoupper($code) }}</flux:text>
                                        <div class="mt-2 grid gap-2 sm:grid-cols-3">
                                            <flux:input name="locales[{{ $code }}][title]" value="{{ $loc['title'] ?? '' }}" :label="__('Title')" />
                                            <flux:input name="locales[{{ $code }}][alt]" value="{{ $loc['alt'] ?? '' }}" :label="__('Alt')" />
                                            <flux:input name="locales[{{ $code }}][caption]" value="{{ $loc['caption'] ?? '' }}" :label="__('Caption')" />
                                        </div>
                                    </div>
                                @endforeach
                                <div class="flex gap-2">
                                    <flux:button type="submit" variant="primary" size="sm">{{ __('Save metadata') }}</flux:button>
                                </div>
                            </form>
                            <form method="post" action="{{ route('admin.concepts.media.destroy', [$concept, $media]) }}" class="mt-2" onsubmit="return confirm(@js(__('Remove this file?')))">
                                @csrf
                                @method('DELETE')
                                <flux:button type="submit" variant="ghost" size="sm">{{ __('Remove asset') }}</flux:button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($concept->media->isEmpty())
                <flux:text class="text-sm text-zinc-500">{{ __('No media attached yet.') }}</flux:text>
            @endif
        </div>
    </flux:card>

    {{-- Add locale --}}
    <flux:card class="mt-8 p-6">
        <flux:heading size="lg">{{ __('Add translation') }}</flux:heading>
        @php
            $existingLang = $concept->translations->pluck('language_id')->all();
            $availableForNew = $languages->filter(fn ($lang) => ! in_array($lang->id, $existingLang, true));
        @endphp
        @if ($availableForNew->isEmpty())
            <flux:text class="mt-4 text-sm text-zinc-500">{{ __('All active locales already have a translation.') }}</flux:text>
        @else
            <form method="post" action="{{ route('admin.concepts.translations.store', $concept) }}" class="mt-6 grid max-w-3xl gap-4">
                @csrf
                <div class="space-y-2">
                    <flux:label>{{ __('Language') }}</flux:label>
                    <select name="language_id" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                        @foreach ($availableForNew as $lang)
                            <option value="{{ $lang->id }}">{{ strtoupper($lang->code) }} — {{ $lang->native_name ?? $lang->name }}</option>
                        @endforeach
                    </select>
                </div>
            <div class="space-y-2">
                <flux:label>{{ __('Translation status') }}</flux:label>
                <select name="status" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    @foreach ($workflowStates as $st)
                        <option value="{{ $st }}" @selected(old('status', $concept->status) === $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
                <flux:input name="term" :label="__('Term')" required />
                <flux:input name="slug" :label="__('URL slug')" required />
                <flux:textarea name="short_definition" rows="2" :label="__('Short definition')"></flux:textarea>
                <flux:textarea name="full_definition" rows="4" :label="__('Full definition')"></flux:textarea>
                <flux:button type="submit" variant="primary" size="sm">{{ __('Add locale') }}</flux:button>
            </form>
        @endif
    </flux:card>

    {{-- Per-locale editors --}}
    @foreach ($concept->translations->sortBy('language_id') as $translation)
        <flux:card class="mt-8 p-6" id="locale-{{ $translation->language?->code }}">
            <div class="flex flex-wrap items-baseline justify-between gap-2">
                <flux:heading size="lg">
                    {{ strtoupper($translation->language?->code ?? '?') }}
                    — {{ $translation->language?->native_name ?? $translation->language?->name }}
                </flux:heading>
                <flux:text class="text-xs text-zinc-500">#{{ $translation->id }}</flux:text>
            </div>

            <form method="post" action="{{ route('admin.concepts.translations.update', [$concept, $translation]) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <flux:label>{{ __('Translation status') }}</flux:label>
                    <select name="status" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                        @foreach ($workflowStates as $st)
                            <option value="{{ $st }}" @selected(old('status', $translation->status) === $st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>

                @php $nearDuplicates = $translationDuplicateWarnings[$translation->id] ?? collect(); @endphp
                @if ($nearDuplicates->isNotEmpty())
                    <div class="rounded-lg border border-amber-300 bg-amber-50 p-3 text-sm text-amber-900 dark:border-amber-700 dark:bg-amber-950 dark:text-amber-200">
                        <p class="font-medium">{{ __('Potential near-duplicate terms detected:') }}</p>
                        <ul class="mt-1 list-disc ps-5">
                            @foreach ($nearDuplicates as $dup)
                                <li>{{ $dup->term }} (#{{ $dup->concept_id }}, {{ $dup->slug }})</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="grid gap-4 md:grid-cols-2">
                    <flux:input name="term" value="{{ old('term', $translation->term) }}" :label="__('Term')" required />
                    <flux:input name="slug" value="{{ old('slug', $translation->slug) }}" :label="__('URL slug')" required />
                </div>
                <flux:textarea name="short_definition" rows="3" :label="__('Short definition')">{{ old('short_definition', $translation->short_definition) }}</flux:textarea>
                <flux:textarea name="full_definition" rows="8" :label="__('Full definition')">{{ old('full_definition', $translation->full_definition) }}</flux:textarea>
                <flux:textarea name="industry_notes" rows="3" :label="__('Industrial / editorial notes')">{{ old('industry_notes', $translation->industry_notes) }}</flux:textarea>

                <flux:separator />

                <flux:heading size="md">{{ __('SEO & social') }}</flux:heading>
                <div class="grid gap-4 md:grid-cols-2">
                    <flux:input name="seo_title" value="{{ old('seo_title', $translation->seo_title) }}" :label="__('SEO title')" />
                    <flux:input name="seo_canonical_url" value="{{ old('seo_canonical_url', $translation->seo_canonical_url) }}" :label="__('Canonical URL override')" placeholder="https://…" />
                </div>
                <flux:textarea name="seo_description" rows="2" :label="__('Meta description')">{{ old('seo_description', $translation->seo_description) }}</flux:textarea>
                <flux:input name="meta_keywords" value="{{ old('meta_keywords', $translation->meta_keywords) }}" :label="__('Meta keywords')" />
                <flux:input name="og_title" value="{{ old('og_title', $translation->og_title) }}" :label="__('Open Graph title')" />
                <flux:textarea name="og_description" rows="2" :label="__('Open Graph description')">{{ old('og_description', $translation->og_description) }}</flux:textarea>

                <flux:separator />

                <flux:heading size="md">{{ __('Examples') }}</flux:heading>
                <flux:text class="text-sm text-zinc-500">{{ __('Leave rows blank to omit. Removing text removes the row on save.') }}</flux:text>
                @php $exRows = $translation->examples; @endphp
                @foreach ($exRows as $ex)
                    <div class="grid gap-3 rounded-lg border border-zinc-200 p-3 dark:border-zinc-800 md:grid-cols-12">
                        <input type="hidden" name="examples[{{ $loop->index }}][id]" value="{{ $ex->id }}" />
                        <div class="md:col-span-7">
                            <flux:textarea name="examples[{{ $loop->index }}][example]" rows="2" :label="__('Example')">{{ $ex->example }}</flux:textarea>
                        </div>
                        <flux:input class="md:col-span-3" name="examples[{{ $loop->index }}][context]" value="{{ $ex->context }}" :label="__('Context')" />
                        <flux:input class="md:col-span-2" name="examples[{{ $loop->index }}][sort_order]" type="number" value="{{ $ex->sort_order }}" :label="__('Sort')" />
                    </div>
                @endforeach
                @for ($i = $exRows->count(); $i < $exRows->count() + 5; $i++)
                    <div class="grid gap-3 rounded-lg border border-dashed border-zinc-300 p-3 dark:border-zinc-600 md:grid-cols-12">
                        <div class="md:col-span-7">
                            <flux:textarea name="examples[{{ $i }}][example]" rows="2" :label="__('New example')"></flux:textarea>
                        </div>
                        <flux:input class="md:col-span-3" name="examples[{{ $i }}][context]" :label="__('Context')" />
                        <flux:input class="md:col-span-2" name="examples[{{ $i }}][sort_order]" type="number" value="0" :label="__('Sort')" />
                    </div>
                @endfor

                <flux:button type="submit" variant="primary">{{ __('Save translation') }}</flux:button>
            </form>
        </flux:card>
    @endforeach

    <div class="mt-8">
        <flux:button variant="ghost" :href="route('admin.concepts.index')" wire:navigate>← {{ __('Back to concepts') }}</flux:button>
    </div>
@endsection
