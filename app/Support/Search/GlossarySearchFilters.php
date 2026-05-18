<?php

namespace App\Support\Search;

use App\Support\Editorial\WorkflowStatus;
use App\Support\Locales;
use App\Support\SemanticGraph;

final class GlossarySearchFilters
{
    public const string QUERY_DOMAIN = 'domain';
    public const string QUERY_LOCALE = 'fl';
    public const string QUERY_STATUS = 'fs';
    public const string QUERY_RELATION = 'fr';

    public function __construct(
        public readonly ?string $domainSlug,
        public readonly ?string $localeCode,
        public readonly ?string $publicationStatus,
        public readonly ?string $relationType,
    ) {}

    /**
     * @param  array<string, mixed>  $query
     */
    public static function fromQuery(array $query, string $defaultLocale, bool $editorialContext = false): self
    {
        $domain = self::normalizedDomain($query[self::QUERY_DOMAIN] ?? null);
        $locale = self::normalizedLocale($query[self::QUERY_LOCALE] ?? null);
        $status = $editorialContext
            ? self::normalizedStatus($query[self::QUERY_STATUS] ?? null)
            : null;
        $relation = self::normalizedRelation($query[self::QUERY_RELATION] ?? null);

        if ($locale === null && Locales::isSupported($defaultLocale)) {
            $locale = $defaultLocale;
        }

        return new self($domain, $locale, $status, $relation);
    }

    public function effectiveLocale(string $defaultLocale): string
    {
        return $this->localeCode ?? $defaultLocale;
    }

    /**
     * @return array<string, string>
     */
    public function toQuery(): array
    {
        return array_filter([
            self::QUERY_DOMAIN => $this->domainSlug,
            self::QUERY_LOCALE => $this->localeCode,
            self::QUERY_STATUS => $this->publicationStatus,
            self::QUERY_RELATION => $this->relationType,
        ], static fn (?string $value): bool => is_string($value) && $value !== '');
    }

    public function hasRelationalConstraints(): bool
    {
        return $this->domainSlug !== null || $this->relationType !== null;
    }

    private static function normalizedDomain(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $domain = trim(mb_strtolower($value, 'UTF-8'));

        return $domain !== '' ? $domain : null;
    }

    private static function normalizedLocale(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $locale = trim(mb_strtolower($value, 'UTF-8'));

        return Locales::isSupported($locale) ? $locale : null;
    }

    private static function normalizedStatus(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $status = trim(mb_strtolower($value, 'UTF-8'));

        return WorkflowStatus::isValid($status) ? $status : null;
    }

    private static function normalizedRelation(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $type = trim(mb_strtolower($value, 'UTF-8'));
        if (! in_array($type, ['synonym', 'broader', 'narrower', 'related'], true)) {
            return null;
        }

        return SemanticGraph::normalizedType($type);
    }
}

