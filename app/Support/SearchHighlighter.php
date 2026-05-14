<?php

namespace App\Support;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

final class SearchHighlighter
{
    /**
     * Case-insensitive highlight of UTF-8 query within escaped plain text.
     */
    public static function mark(string $text, string $query): HtmlString
    {
        $text = trim($text);
        $query = trim($query);
        if ($text === '' || $query === '') {
            return new HtmlString(e($text));
        }

        $escaped = e($text);
        $quoted = preg_quote($query, '/');

        $html = (string) preg_replace(
            '/('.$quoted.')/iu',
            '<mark class="rounded-sm bg-amber-100/90 px-0.5 font-medium text-inherit dark:bg-amber-950/40">$1</mark>',
            $escaped,
        );

        return new HtmlString($html !== '' ? $html : $escaped);
    }

    /**
     * Short excerpt around first case-insensitive match for dropdowns and lists.
     */
    public static function excerpt(?string $text, string $query, int $radius = 72): string
    {
        if ($text === null || trim($text) === '') {
            return '';
        }

        $plain = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');
        $q = trim($query);
        if ($plain === '' || $q === '') {
            return Str::limit($plain, $radius * 2);
        }

        $pos = mb_stripos($plain, $q, 0, 'UTF-8');
        if ($pos === false) {
            return Str::limit($plain, $radius * 2);
        }

        $start = max(0, $pos - $radius);
        $slice = mb_substr($plain, $start, $radius * 2 + mb_strlen($q, 'UTF-8'), 'UTF-8');
        $prefix = $start > 0 ? '…' : '';
        $suffix = ($start + mb_strlen($slice, 'UTF-8')) < mb_strlen($plain, 'UTF-8') ? '…' : '';

        return $prefix.$slice.$suffix;
    }
}
