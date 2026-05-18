<?php

namespace App\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Locale-aware metadata for concept media (stored in {@see Media::$custom_properties}).
 *
 * Expected shape:
 * - `locales` => [ 'en' => ['title' => '', 'alt' => '', 'caption' => '', 'process_stage' => ''], ... ]
 * - `embed_url` => string|null (videos: iframe src)
 * - `kind` => 'diagram'|'photo'|null (optional gallery subtyping)
 * - `semantic_role` => 'anatomy'|'process'|'construction'|'workflow'|'machinery'|'quality'|'reference'|null
 * - `source_label` => string|null (technical source label)
 * - `source_url` => string|null (technical source URL)
 */
final class ConceptMedia
{
    /** @var list<string> */
    public const array SEMANTIC_ROLES = [
        'anatomy',
        'process',
        'construction',
        'workflow',
        'machinery',
        'quality',
        'reference',
    ];

    /**
     * @return array{
     *   title: string,
     *   alt: string,
     *   caption: string,
     *   process_stage: string,
     *   kind: ?string,
     *   semantic_role: string,
     *   source_label: ?string,
     *   source_url: ?string,
     *   embed_url: ?string
     * }
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
        $processStage = (string) ($row['process_stage'] ?? $default['process_stage'] ?? '');
        $kind = isset($props['kind']) && is_string($props['kind']) ? $props['kind'] : null;
        $semanticRole = self::sanitizeSemanticRole($props['semantic_role'] ?? null)
            ?? self::defaultSemanticRoleForMedia($media, $kind);
        $sourceLabel = isset($props['source_label']) && is_string($props['source_label'])
            ? trim($props['source_label'])
            : null;
        $sourceUrl = isset($props['source_url']) && is_string($props['source_url'])
            ? trim($props['source_url'])
            : null;
        $embedRaw = isset($props['embed_url']) && is_string($props['embed_url']) ? $props['embed_url'] : null;
        $embedUrl = TrustedEmbedUrl::normalize($embedRaw);

        return [
            'title' => $title,
            'alt' => $alt !== '' ? $alt : $fallbackTerm.' — '.$title,
            'caption' => $caption,
            'process_stage' => $processStage,
            'kind' => $kind,
            'semantic_role' => $semanticRole,
            'source_label' => $sourceLabel !== '' ? $sourceLabel : null,
            'source_url' => filter_var($sourceUrl, FILTER_VALIDATE_URL) ? $sourceUrl : null,
            'embed_url' => $embedUrl,
        ];
    }

    public static function sanitizeSemanticRole(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }
        $normalized = trim(mb_strtolower($value, 'UTF-8'));
        if (! in_array($normalized, self::SEMANTIC_ROLES, true)) {
            return null;
        }

        return $normalized;
    }

    private static function defaultSemanticRoleForMedia(Media $media, ?string $kind): string
    {
        if ($kind === 'diagram') {
            return 'construction';
        }

        return match ($media->collection_name) {
            'videos' => 'process',
            'documents' => 'reference',
            'featured' => 'construction',
            default => 'reference',
        };
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
