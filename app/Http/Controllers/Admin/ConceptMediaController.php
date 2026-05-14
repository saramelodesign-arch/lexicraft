<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Support\Locales;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class ConceptMediaController extends Controller
{
    /**
     * @return array<string, mixed>
     */
    private function customPropertiesFromRequest(Request $request): array
    {
        $locales = [];
        foreach (Locales::codes() as $code) {
            $title = trim((string) $request->input("locales.{$code}.title", ''));
            $alt = trim((string) $request->input("locales.{$code}.alt", ''));
            $caption = trim((string) $request->input("locales.{$code}.caption", ''));
            if ($title === '' && $alt === '' && $caption === '') {
                continue;
            }
            $locales[$code] = array_filter([
                'title' => $title !== '' ? $title : null,
                'alt' => $alt !== '' ? $alt : null,
                'caption' => $caption !== '' ? $caption : null,
            ], fn ($v) => $v !== null);
        }

        $props = [];
        if ($locales !== []) {
            $props['locales'] = $locales;
        }

        $embed = trim((string) $request->input('embed_url', ''));
        if ($embed !== '') {
            $props['embed_url'] = $embed;
        }

        $kind = trim((string) $request->input('kind', ''));
        if (in_array($kind, ['diagram', 'photo'], true)) {
            $props['kind'] = $kind;
        }

        return $props;
    }

    public function store(Request $request, Concept $concept): RedirectResponse
    {
        $validated = $request->validate([
            'collection' => ['required', 'string', Rule::in(['featured', 'gallery', 'videos', 'documents'])],
            'file' => ['required', 'file', 'max:51200'],
            'embed_url' => ['nullable', 'string', 'max:2048'],
            'kind' => ['nullable', 'string', Rule::in(['diagram', 'photo'])],
        ]);

        $map = [
            'featured' => Concept::COLLECTION_FEATURED,
            'gallery' => Concept::COLLECTION_GALLERY,
            'videos' => Concept::COLLECTION_VIDEOS,
            'documents' => Concept::COLLECTION_DOCUMENTS,
        ];

        $collection = $map[$validated['collection']];

        $adder = $concept->addMediaFromRequest('file')->sanitizingFileName();
        $props = $this->customPropertiesFromRequest($request);
        if ($props !== []) {
            $adder->withCustomProperties($props);
        }

        $adder->toMediaCollection($collection);

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('Media uploaded.'));
    }

    public function update(Request $request, Concept $concept, Media $media): RedirectResponse
    {
        $this->assertMediaBelongsToConcept($concept, $media);

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'embed_url' => ['nullable', 'string', 'max:2048'],
            'kind' => ['nullable', 'string', Rule::in(['diagram', 'photo'])],
        ]);

        $props = array_merge($media->custom_properties ?? [], $this->customPropertiesFromRequest($request));

        $media->update([
            'name' => $request->input('name') ?: $media->name,
            'custom_properties' => $props,
        ]);

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('Media metadata saved.'));
    }

    public function destroy(Concept $concept, Media $media): RedirectResponse
    {
        $this->assertMediaBelongsToConcept($concept, $media);

        $media->delete();

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('Media removed.'));
    }

    private function assertMediaBelongsToConcept(Concept $concept, Media $media): void
    {
        abort_unless(
            $media->model_type === Concept::class && (int) $media->model_id === (int) $concept->id,
            404,
        );
    }
}
