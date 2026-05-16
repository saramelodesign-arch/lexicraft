@extends('layouts.admin', ['pageTitle' => __('admin.edit_concept', ['id' => $concept->id])])

@section('subhead')
    {{ __('admin.manage_concept_subhead') }}
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
        <flux:text class="text-sm text-zinc-500">{{ __('admin.uuid', ['u' => $concept->uuid]) }}</flux:text>
        <div class="flex flex-wrap gap-2">
            @php
                $first = $concept->translations->sortBy('language_id')->first();
                $previewLocale = $first?->language?->code ?? \App\Support\Locales::fallback();
                $previewSlug = $first?->slug;
            @endphp
            @if ($previewSlug && $concept->status === 'published')
                <flux:button size="sm" variant="ghost" :href="route('glossary.concept', ['locale' => $previewLocale, 'slug' => $previewSlug])" target="_blank">
                    {{ __('admin.view_live') }}
                </flux:button>
            @endif
            <form method="post" action="{{ route('admin.concepts.destroy', $concept) }}" onsubmit="return confirm(@js(__('admin.delete_concept_confirm')))">
                @csrf
                @method('DELETE')
                <flux:button size="sm" variant="danger" type="submit">{{ __('admin.delete_concept') }}</flux:button>
            </form>
        </div>
    </div>

    {{-- Core concept --}}
    <flux:card class="mt-6 p-6">
        <flux:heading size="lg">{{ __('admin.publication_taxonomy') }}</flux:heading>
        <form method="post" action="{{ route('admin.concepts.update', $concept) }}" class="mt-6 grid max-w-3xl gap-6">
            @csrf
            @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <flux:label>{{ __('admin.workflow_status') }}</flux:label>
                    <select name="status" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                        @foreach ($workflowStates as $st)
                            <option value="{{ $st }}" @selected(old('status', $concept->status) === $st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <flux:input name="difficulty_level" value="{{ old('difficulty_level', $concept->difficulty_level) }}" :label="__('admin.difficulty_level')" />
            </div>
            <input type="hidden" name="is_featured" value="0" />
            <flux:checkbox name="is_featured" value="1" :label="__('admin.featured_concept')" :checked="(bool) old('is_featured', $concept->is_featured)" />
            <div class="space-y-2">
                <flux:label>{{ __('admin.domains') }}</flux:label>
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
            <flux:button type="submit" variant="primary">{{ __('admin.save_concept') }}</flux:button>
        </form>
    </flux:card>

    {{-- Semantic relations --}}
    <flux:card class="mt-8 p-6">
        <flux:heading size="lg">{{ __('admin.relations') }}</flux:heading>
        <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('admin.outgoing_edges_help') }}</flux:text>

        <form method="post" action="{{ route('admin.concepts.relations.store', $concept) }}" class="mt-6 grid gap-4 md:grid-cols-4">
            @csrf
            <div class="space-y-2 md:col-span-1">
                <flux:label>{{ __('admin.type') }}</flux:label>
                <select name="relation_type" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    @foreach ($relationTypes as $type)
                        <option value="{{ $type }}">{{ str_replace('_', ' ', $type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2 md:col-span-1">
                <flux:label>{{ __('admin.target_locale') }}</flux:label>
                <select name="related_locale" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    @foreach (\App\Support\Locales::codes() as $code)
                        <option value="{{ $code }}">{{ strtoupper($code) }}</option>
                    @endforeach
                </select>
            </div>
            <flux:input class="md:col-span-2" name="related_slug" value="{{ old('related_slug') }}" :label="__('admin.target_slug')" required placeholder="other-term-slug" />
            <div class="md:col-span-4">
                <flux:button type="submit" variant="primary" size="sm">{{ __('admin.add_relation') }}</flux:button>
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
                        {{ $peer?->term ?? __('admin.concept_fallback', ['id' => $rel->related_concept_id]) }}
                        <span class="font-mono text-xs text-zinc-400">({{ $peer?->slug }})</span>
                    </div>
                    <form method="post" action="{{ route('admin.concepts.relations.destroy', [$concept, $rel]) }}">
                        @csrf
                        @method('DELETE')
                        <flux:button size="sm" variant="ghost" type="submit">{{ __('admin.remove') }}</flux:button>
                    </form>
                </li>
            @empty
                <li class="py-4 text-sm text-zinc-500">{{ __('admin.no_outgoing_relations') }}</li>
            @endforelse
        </ul>
    </flux:card>

    {{-- Media --}}
    <flux:card class="mt-8 p-6">
        <flux:heading size="lg">{{ __('admin.media_library') }}</flux:heading>
        <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('admin.media_library_subhead') }}</flux:text>

        <form method="post" action="{{ route('admin.concepts.media.store', $concept) }}" enctype="multipart/form-data" class="mt-6 grid gap-4 md:grid-cols-2">
            @csrf
            <div class="space-y-2">
                <flux:label>{{ __('admin.collection') }}</flux:label>
                <select name="collection" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="featured">{{ __('admin.featured_image') }}</option>
                    <option value="gallery">{{ __('admin.gallery') }}</option>
                    <option value="videos">{{ __('admin.videos_posters') }}</option>
                    <option value="documents">{{ __('admin.documents') }}</option>
                </select>
            </div>
            <flux:input type="file" name="file" :label="__('admin.file')" required />
            <flux:input name="embed_url" :label="__('admin.videos_posters')" placeholder="https://…" class="md:col-span-2" />
            <div class="space-y-2 md:col-span-2">
                <flux:label>{{ __('admin.gallery_kind') }}</flux:label>
                <select name="kind" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="">{{ __('admin.top_level') }}</option>
                    <option value="photo">{{ __('admin.photo') }}</option>
                    <option value="diagram">{{ __('admin.diagram') }}</option>
                </select>
            </div>
            @foreach (\App\Support\Locales::codes() as $code)
                <div class="md:col-span-2 rounded-lg border border-zinc-200 p-4 dark:border-zinc-800">
                    <flux:text class="text-xs font-semibold uppercase text-zinc-500">{{ strtoupper($code) }}</flux:text>
                    <div class="mt-2 grid gap-3 sm:grid-cols-3">
                        <flux:input name="locales[{{ $code }}][title]" :label="__('admin.title')" />
                        <flux:input name="locales[{{ $code }}][alt]" :label="__('admin.alt_text')" />
                        <flux:input name="locales[{{ $code }}][caption]" :label="__('admin.caption')" />
                    </div>
                </div>
            @endforeach
            <flux:button type="submit" variant="primary" size="sm">{{ __('admin.upload') }}</flux:button>
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
                                <flux:input name="name" value="{{ old('name', $media->name) }}" :label="__('admin.file_label')" />
                                <flux:input name="embed_url" value="{{ old('embed_url', $media->getCustomProperty('embed_url')) }}" :label="__('admin.videos_posters')" />
                                <div class="space-y-2">
                                    <flux:label>{{ __('admin.gallery_kind') }}</flux:label>
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
                                            <flux:input name="locales[{{ $code }}][title]" value="{{ $loc['title'] ?? '' }}" :label="__('admin.title')" />
                                            <flux:input name="locales[{{ $code }}][alt]" value="{{ $loc['alt'] ?? '' }}" :label="__('admin.alt')" />
                                            <flux:input name="locales[{{ $code }}][caption]" value="{{ $loc['caption'] ?? '' }}" :label="__('admin.caption')" />
                                        </div>
                                    </div>
                                @endforeach
                                <div class="flex gap-2">
                                    <flux:button type="submit" variant="primary" size="sm">{{ __('admin.save_metadata') }}</flux:button>
                                </div>
                            </form>
                            <form method="post" action="{{ route('admin.concepts.media.destroy', [$concept, $media]) }}" class="mt-2" onsubmit="return confirm(@js(__('admin.remove_file_confirm')))">
                                @csrf
                                @method('DELETE')
                                <flux:button type="submit" variant="ghost" size="sm">{{ __('admin.remove_asset') }}</flux:button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($concept->media->isEmpty())
                <flux:text class="text-sm text-zinc-500">{{ __('admin.media_attached_empty') }}</flux:text>
            @endif
        </div>
    </flux:card>

    {{-- Add locale --}}
    <flux:card class="mt-8 p-6">
        <flux:heading size="lg">{{ __('admin.add_translation') }}</flux:heading>
        @php
            $existingLang = $concept->translations->pluck('language_id')->all();
            $availableForNew = $languages->filter(fn ($lang) => ! in_array($lang->id, $existingLang, true));
        @endphp
        @if ($availableForNew->isEmpty())
            <flux:text class="mt-4 text-sm text-zinc-500">{{ __('admin.all_locales_have_translation') }}</flux:text>
        @else
            <form method="post" action="{{ route('admin.concepts.translations.store', $concept) }}" class="mt-6 grid max-w-3xl gap-4">
                @csrf
                <div class="space-y-2">
                    <flux:label>{{ __('admin.language') }}</flux:label>
                    <select name="language_id" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                        @foreach ($availableForNew as $lang)
                            <option value="{{ $lang->id }}">{{ strtoupper($lang->code) }} — {{ $lang->native_name ?? $lang->name }}</option>
                        @endforeach
                    </select>
                </div>
            <div class="space-y-2">
                <flux:label>{{ __('admin.translation_status') }}</flux:label>
                <select name="status" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    @foreach ($workflowStates as $st)
                        <option value="{{ $st }}" @selected(old('status', $concept->status) === $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
                <flux:input name="term" :label="__('admin.term')" required />
                <flux:input name="slug" :label="__('admin.url_slug')" required />
                <flux:textarea name="short_definition" rows="2" :label="__('admin.short_definition')"></flux:textarea>
                <flux:textarea name="full_definition" rows="4" :label="__('admin.full_definition')"></flux:textarea>
                <flux:button type="submit" variant="primary" size="sm">{{ __('admin.add_locale') }}</flux:button>
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
                    <flux:label>{{ __('admin.translation_status') }}</flux:label>
                    <select name="status" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                        @foreach ($workflowStates as $st)
                            <option value="{{ $st }}" @selected(old('status', $translation->status) === $st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>

                @php $nearDuplicates = $translationDuplicateWarnings[$translation->id] ?? collect(); @endphp
                @if ($nearDuplicates->isNotEmpty())
                    <div class="rounded-lg border border-amber-300 bg-amber-50 p-3 text-sm text-amber-900 dark:border-amber-700 dark:bg-amber-950 dark:text-amber-200">
                        <p class="font-medium">{{ __('admin.potential_duplicates') }}</p>
                        <ul class="mt-1 list-disc ps-5">
                            @foreach ($nearDuplicates as $dup)
                                <li>{{ $dup->term }} (#{{ $dup->concept_id }}, {{ $dup->slug }})</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="grid gap-4 md:grid-cols-2">
                    <flux:input name="term" value="{{ old('term', $translation->term) }}" :label="__('admin.term')" required />
                    <flux:input name="slug" value="{{ old('slug', $translation->slug) }}" :label="__('admin.url_slug')" required />
                </div>
                <flux:textarea name="short_definition" rows="3" :label="__('admin.short_definition')">{{ old('short_definition', $translation->short_definition) }}</flux:textarea>
                <flux:textarea name="full_definition" rows="8" :label="__('admin.full_definition')">{{ old('full_definition', $translation->full_definition) }}</flux:textarea>
                <flux:textarea name="industry_notes" rows="3" :label="__('admin.industry_notes')">{{ old('industry_notes', $translation->industry_notes) }}</flux:textarea>

                <flux:separator />

                <flux:heading size="md">{{ __('admin.seo_social') }}</flux:heading>
                <div class="grid gap-4 md:grid-cols-2">
                    <flux:input name="seo_title" value="{{ old('seo_title', $translation->seo_title) }}" :label="__('admin.seo_title')" />
                    <flux:input name="seo_canonical_url" value="{{ old('seo_canonical_url', $translation->seo_canonical_url) }}" :label="__('admin.canonical_url_override')" placeholder="https://…" />
                </div>
                <flux:textarea name="seo_description" rows="2" :label="__('admin.meta_description')">{{ old('seo_description', $translation->seo_description) }}</flux:textarea>
                <flux:input name="meta_keywords" value="{{ old('meta_keywords', $translation->meta_keywords) }}" :label="__('admin.meta_keywords')" />
                <flux:input name="og_title" value="{{ old('og_title', $translation->og_title) }}" :label="__('admin.og_title')" />
                <flux:textarea name="og_description" rows="2" :label="__('admin.og_description')">{{ old('og_description', $translation->og_description) }}</flux:textarea>

                <flux:separator />

                <flux:heading size="md">{{ __('admin.examples') }}</flux:heading>
                <flux:text class="text-sm text-zinc-500">{{ __('admin.leave_example_rows_blank') }}</flux:text>
                @php $exRows = $translation->examples; @endphp
                @foreach ($exRows as $ex)
                    <div class="grid gap-3 rounded-lg border border-zinc-200 p-3 dark:border-zinc-800 md:grid-cols-12">
                        <input type="hidden" name="examples[{{ $loop->index }}][id]" value="{{ $ex->id }}" />
                        <div class="md:col-span-7">
                            <flux:textarea name="examples[{{ $loop->index }}][example]" rows="2" :label="__('admin.example')">{{ $ex->example }}</flux:textarea>
                        </div>
                        <flux:input class="md:col-span-3" name="examples[{{ $loop->index }}][context]" value="{{ $ex->context }}" :label="__('admin.context')" />
                        <flux:input class="md:col-span-2" name="examples[{{ $loop->index }}][sort_order]" type="number" value="{{ $ex->sort_order }}" :label="__('admin.sort')" />
                    </div>
                @endforeach
                @for ($i = $exRows->count(); $i < $exRows->count() + 5; $i++)
                    <div class="grid gap-3 rounded-lg border border-dashed border-zinc-300 p-3 dark:border-zinc-600 md:grid-cols-12">
                        <div class="md:col-span-7">
                            <flux:textarea name="examples[{{ $i }}][example]" rows="2" :label="__('admin.new_example')"></flux:textarea>
                        </div>
                        <flux:input class="md:col-span-3" name="examples[{{ $i }}][context]" :label="__('admin.context')" />
                        <flux:input class="md:col-span-2" name="examples[{{ $i }}][sort_order]" type="number" value="0" :label="__('admin.sort')" />
                    </div>
                @endfor

                <flux:button type="submit" variant="primary">{{ __('admin.save_translation') }}</flux:button>
            </form>
        </flux:card>
    @endforeach

    <div class="mt-8">
        <flux:button variant="ghost" :href="route('admin.concepts.index')" wire:navigate>← {{ __('admin.back_to_concepts') }}</flux:button>
    </div>
@endsection
