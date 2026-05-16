<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Support\Locales;
use App\Support\TrustedEmbedUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
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

        $embed = TrustedEmbedUrl::normalize((string) $request->input('embed_url', ''));
        if ($embed !== null) {
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
        Gate::authorize('media.manage', $concept);

        $validated = $request->validate([
            'collection' => ['required', 'string', Rule::in(['featured', 'gallery', 'videos', 'documents'])],
            'file' => ['required', 'file', 'max:51200', function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                if (! $value instanceof UploadedFile) {
                    $fail(__('admin.msg_media_file_required'));

                    return;
                }

                $collection = (string) $request->input('collection', '');
                if (! in_array($collection, ['featured', 'gallery', 'videos', 'documents'], true)) {
                    $fail(__('admin.msg_media_collection_invalid'));

                    return;
                }

                $mimeType = (string) ($value->getMimeType() ?? '');
                if (! in_array($mimeType, $this->allowedMimeTypesForCollection($collection), true)) {
                    $fail(__('admin.msg_media_file_type_invalid'));
                }
            }],
            'embed_url' => ['nullable', 'string', 'max:2048', function (string $attribute, mixed $value, \Closure $fail): void {
                $candidate = trim((string) ($value ?? ''));
                if ($candidate !== '' && ! TrustedEmbedUrl::isTrusted($candidate)) {
                    $fail(__('admin.msg_embed_url_invalid'));
                }
            }],
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
            ->with('status', __('admin.msg_media_uploaded'));
    }

    public function update(Request $request, Concept $concept, Media $media): RedirectResponse
    {
        Gate::authorize('media.manage', $concept);
        $this->assertMediaBelongsToConcept($concept, $media);

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'embed_url' => ['nullable', 'string', 'max:2048', function (string $attribute, mixed $value, \Closure $fail): void {
                $candidate = trim((string) ($value ?? ''));
                if ($candidate !== '' && ! TrustedEmbedUrl::isTrusted($candidate)) {
                    $fail(__('admin.msg_embed_url_invalid'));
                }
            }],
            'kind' => ['nullable', 'string', Rule::in(['diagram', 'photo'])],
        ]);

        $props = array_merge($media->custom_properties ?? [], $this->customPropertiesFromRequest($request));

        $media->update([
            'name' => $request->input('name') ?: $media->name,
            'custom_properties' => $props,
        ]);

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('admin.msg_media_saved'));
    }

    public function destroy(Concept $concept, Media $media): RedirectResponse
    {
        Gate::authorize('media.manage', $concept);
        $this->assertMediaBelongsToConcept($concept, $media);

        $media->delete();

        return redirect()
            ->route('admin.concepts.edit', $concept)
            ->with('status', __('admin.msg_media_removed'));
    }

    private function assertMediaBelongsToConcept(Concept $concept, Media $media): void
    {
        abort_unless(
            $media->model_type === Concept::class && (int) $media->model_id === (int) $concept->id,
            404,
        );
    }

    /**
     * @return list<string>
     */
    private function allowedMimeTypesForCollection(string $collection): array
    {
        return match ($collection) {
            'featured', 'gallery' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
            'videos' => ['image/jpeg', 'image/png', 'image/webp', 'video/mp4'],
            'documents' => ['application/pdf'],
            default => [],
        };
    }
}
