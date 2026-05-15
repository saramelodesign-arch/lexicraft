<?php

namespace App\Support\Import;

final class TerminologyImportRow
{
    /**
     * @param  list<string>  $domains
     */
    public function __construct(
        public readonly string $locale,
        public readonly string $term,
        public readonly string $slug,
        public readonly ?string $shortDefinition,
        public readonly ?string $fullDefinition,
        public readonly string $conceptStatus,
        public readonly string $translationStatus,
        public readonly array $domains = [],
    ) {}
}
