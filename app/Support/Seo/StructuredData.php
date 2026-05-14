<?php

namespace App\Support\Seo;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Support\Locales;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * JSON-LD builders (Schema.org). Output is ready for json_encode in views/controllers.
 */
final class StructuredData
{
    /**
     * @return array<string, mixed>
     */
    public static function organization(): array
    {
        $org = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('seo.organization.name'),
            'url' => config('seo.organization.url'),
            'description' => config('seo.organization.description'),
        ];
        $logo = config('seo.organization.logo_url');
        if (is_string($logo) && $logo !== '') {
            $org['logo'] = $logo;
        }

        return $org;
    }

    /**
     * @return array<string, mixed>
     */
    public static function webSite(string $locale): array
    {
        $home = route('home', ['locale' => $locale], absolute: true);
        $searchTemplate = route('search', ['locale' => $locale], absolute: true).'?q={search_term_string}';

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => $home,
            'inLanguage' => str_replace('_', '-', $locale),
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('seo.organization.name'),
                'url' => config('seo.organization.url'),
            ],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $searchTemplate,
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function definedTermSetHome(string $locale, string $description): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'DefinedTermSet',
            'name' => config('app.name').' — '.__('Industrial glossary'),
            'description' => $description,
            'url' => route('home', ['locale' => $locale], absolute: true).'#glossary',
            'inLanguage' => str_replace('_', '-', $locale),
        ];
    }

    /**
     * @param  list<array{name: string, url: string}>  $crumbs
     * @return array<string, mixed>
     */
    public static function breadcrumbList(array $crumbs): array
    {
        $elements = [];
        $position = 1;
        foreach ($crumbs as $row) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $row['name'],
                'item' => $row['url'],
            ];
            $position++;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * @param  list<string>  $seeAlso
     * @param  list<string|array<string, mixed>>  $images
     * @param  list<string>  $examplesPlain
     * @return array<string, mixed>
     */
    public static function definedTermForConcept(
        ConceptTranslation $translation,
        Concept $concept,
        string $locale,
        string $canonical,
        string $metaDescription,
        array $seeAlso,
        array $images,
        array $examplesPlain,
    ): array {
        $desc = Str::limit(
            strip_tags((string) ($translation->short_definition ?? $translation->full_definition ?? $metaDescription)),
            5000,
        );

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'DefinedTerm',
            'name' => $translation->term,
            'termCode' => $translation->slug,
            'description' => $desc,
            'url' => $canonical,
            'inLanguage' => str_replace('_', '-', $locale),
            'inDefinedTermSet' => [
                '@type' => 'DefinedTermSet',
                'name' => config('app.name').' — '.__('Industrial glossary'),
                'url' => route('home', ['locale' => $locale], absolute: true).'#glossary',
            ],
        ];

        $synonyms = self::synonymsFromMetaKeywords($translation->meta_keywords);
        if ($synonyms !== []) {
            $schema['alternateName'] = count($synonyms) === 1 ? $synonyms[0] : $synonyms;
        }

        if (filled($translation->meta_keywords)) {
            $schema['keywords'] = $translation->meta_keywords;
        }

        if ($seeAlso !== []) {
            $schema['seeAlso'] = $seeAlso;
        }

        if ($images !== []) {
            $schema['image'] = count($images) === 1 ? $images[0] : $images;
        }

        if ($examplesPlain !== []) {
            $schema['subjectOf'] = array_map(static function (string $text): array {
                return [
                    '@type' => 'CreativeWork',
                    'name' => __('Example'),
                    'text' => Str::limit($text, 2000),
                ];
            }, array_slice($examplesPlain, 0, 8));
        }

        return $schema;
    }

    /**
     * @return list<string>
     */
    private static function synonymsFromMetaKeywords(?string $metaKeywords): array
    {
        if ($metaKeywords === null || trim($metaKeywords) === '') {
            return [];
        }

        $parts = preg_split('/[,;|]+/u', $metaKeywords) ?: [];

        return array_values(array_unique(array_filter(array_map(
            static fn (string $s): string => trim($s),
            $parts,
        ), static fn (string $s): bool => $s !== '')));
    }

    /**
     * Build breadcrumb rows for a concept: Home → Domains → … → Letter → Term.
     *
     * @return list<array{name: string, url: string}>
     */
    public static function conceptBreadcrumbRows(
        Concept $concept,
        ConceptTranslation $translation,
        string $locale,
        int $languageId,
        string $canonical,
    ): array {
        $rows = [];
        $rows[] = [
            'name' => __('Home'),
            'url' => route('home', ['locale' => $locale], absolute: true),
        ];
        $rows[] = [
            'name' => __('Industrial domains'),
            'url' => route('domains.index', ['locale' => $locale], absolute: true),
        ];

        $primary = $concept->domains->sortBy('id')->first();
        if ($primary instanceof Domain) {
            foreach (self::domainLineage($primary) as $domain) {
                $domain->loadMissing(['translations' => fn ($q) => $q->where('language_id', $languageId)]);
                $dt = $domain->translations->firstWhere('language_id', $languageId);
                if ($dt !== null && filled($dt->slug)) {
                    $rows[] = [
                        'name' => $dt->name,
                        'url' => route('domains.show', ['locale' => $locale, 'slug' => $dt->slug], absolute: true),
                    ];
                }
            }
        }

        $letter = mb_strtoupper(mb_substr($translation->term, 0, 1, 'UTF-8'), 'UTF-8');
        $rows[] = [
            'name' => __('Glossary: letter :letter', ['letter' => $letter]),
            'url' => route('glossary.letter', ['locale' => $locale, 'letter' => strtolower($letter)], absolute: true),
        ];
        $rows[] = [
            'name' => $translation->term,
            'url' => $canonical,
        ];

        return $rows;
    }

    /**
     * @return Collection<int, Domain>
     */
    private static function domainLineage(Domain $leaf): Collection
    {
        $ordered = collect();
        $current = $leaf;
        $guard = 0;
        while ($current instanceof Domain && $guard++ < 24) {
            $ordered->prepend($current);
            if ($current->parent_id === null) {
                break;
            }
            $current = Domain::query()->whereKey($current->parent_id)->first() ?? null;
        }

        return $ordered;
    }

    /**
     * @return list<array{name: string, url: string}>
     */
    public static function domainBreadcrumbRows(Domain $domain, string $locale, int $languageId, string $canonical): array
    {
        $rows = [];
        $rows[] = [
            'name' => __('Home'),
            'url' => route('home', ['locale' => $locale], absolute: true),
        ];
        $rows[] = [
            'name' => __('Industrial domains'),
            'url' => route('domains.index', ['locale' => $locale], absolute: true),
        ];

        foreach (self::domainLineage($domain) as $d) {
            $d->loadMissing(['translations' => fn ($q) => $q->where('language_id', $languageId)]);
            $dt = $d->translations->firstWhere('language_id', $languageId);
            if ($dt !== null && filled($dt->slug)) {
                $isCurrent = $d->id === $domain->id;
                $rows[] = [
                    'name' => $dt->name,
                    'url' => $isCurrent ? $canonical : route('domains.show', ['locale' => $locale, 'slug' => $dt->slug], absolute: true),
                ];
            }
        }

        return $rows;
    }

    /**
     * @return array<string, string>
     */
    public static function hreflangAlternatesForRoute(callable $routeBuilder): array
    {
        $alternates = [];
        foreach (Locales::codes() as $code) {
            $alternates[$code] = $routeBuilder($code);
        }

        return $alternates;
    }
}
