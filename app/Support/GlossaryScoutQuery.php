<?php

namespace App\Support;

/**
 * Shared Meilisearch / Scout options for glossary UX (dropdown + results).
 */
final class GlossaryScoutQuery
{
    public const string HIGHLIGHT_PRE = '<mark class="rounded-sm bg-amber-100/90 px-0.5 font-medium text-inherit dark:bg-amber-950/40">';

    public const string HIGHLIGHT_POST = '</mark>';

    /**
     * @return array<string, mixed>
     */
    public static function highlightOptions(): array
    {
        return [
            'attributesToHighlight' => ['term', 'short_definition', 'examples_snippet'],
            'attributesToCrop' => ['short_definition', 'examples_snippet'],
            'cropLength' => 200,
            'highlightPreTag' => self::HIGHLIGHT_PRE,
            'highlightPostTag' => self::HIGHLIGHT_POST,
        ];
    }

    public static function usesMeilisearch(): bool
    {
        return config('scout.driver') === 'meilisearch';
    }
}
