<?php

namespace App\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Locale-aware metadata for concept media (stored in {@see Media::$custom_properties}).
 *
 * Expected shape:
 * - `locales` => [ 'en' => ['title' => '', 'alt' => '', 'caption' => ''], ... ]
 * - `embed_url` => string|null (videos: iframe src)
 * - `kind` => 'diagram'|'photo'|null (optional gallery subtyping)
 */
final class ConceptMedia
{
    /**
     * @return array{title: string, alt: string, caption: string, kind: ?string, embed_url: ?string}
     */
    public static function meta(Media $media, string $locale, string $fallbackTerm): array
    {
        $props = $media->custom_properties ?? [];
        $locales = is_array($props['locales'] ?? null) ? $props['locales'] : [];
        $row = is_array($locales[$locale] ?? null) ? $locales[$locale] : [];
        $default = is_array($props['default'] ?? null) ? $props['default'] : [];

        $title = (string) ($row['title'] ?? $default['title'] ?? $media->name ?? $fallbackTerm);
        $alt = (string) ($row['alt'] ?? $default['alt'] ?? $title);
        $caption = (string) ($row['caption'] ?? $default['caption'] ?? '');
        $kind = isset($props['kind']) && is_string($props['kind']) ? $props['kind'] : null;
        $embedRaw = isset($props['embed_url']) && is_string($props['embed_url']) ? $props['embed_url'] : null;
        $embedUrl = TrustedEmbedUrl::normalize($embedRaw);

        return [
            'title' => $title,
            'alt' => $alt !== '' ? $alt : $fallbackTerm.' — '.$title,
            'caption' => $caption,
            'kind' => $kind,
            'embed_url' => $embedUrl,
        ];
    }

    public static function ogImageUrl(?Media $media): ?string
    {
        if ($media === null) {
            return null;
        }

        if ($media->hasGeneratedConversion('card')) {
            return $media->getFullUrl('card');
        }

        return $media->getFullUrl();
    }

    /**
     * @param  iterable<Media>  $mediaItems
     * @return list<string>
     */
    public static function imageObjectUrls(iterable $mediaItems, int $limit = 12): array
    {
        $out = [];
        foreach ($mediaItems as $media) {
            if (! str_starts_with((string) $media->mime_type, 'image/')) {
                continue;
            }
            $url = self::ogImageUrl($media);
            if (is_string($url) && $url !== '') {
                $out[] = $url;
            }
            if (count($out) >= $limit) {
                break;
            }
        }

        return array_values(array_unique($out));
    }
}
