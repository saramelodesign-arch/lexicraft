<?php

namespace App\Models;

use App\Support\ConceptSearchDocument;
use App\Support\GlossaryLetterSql;
use App\Support\Locales;
use App\Support\SearchHighlighter;
use Database\Factories\ConceptTranslationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class ConceptTranslation extends Model
{
    /** @use HasFactory<ConceptTranslationFactory> */
    use HasFactory;

    use Searchable;

    protected $guarded = [];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function examples(): HasMany
    {
        return $this->hasMany(Example::class, 'concept_translation_id');
    }

    public function glossaryUrl(bool $absolute = false): string
    {
        $code = $this->relationLoaded('language')
            ? ($this->language?->code)
            : Language::query()->whereKey($this->language_id)->value('code');

        $locale = is_string($code) && Locales::isSupported($code) ? $code : Locales::fallback();

        return route('glossary.concept', [
            'locale' => $locale,
            'slug' => $this->slug,
        ], absolute: $absolute);
    }

    public function shouldBeSearchable(): bool
    {
        $this->loadMissing('concept:id,status', 'language:id,is_active');

        if ($this->concept?->status !== 'published') {
            return false;
        }

        return (bool) $this->language?->is_active;
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return ConceptSearchDocument::fromTranslation($this);
    }

    /**
     * @param  EloquentCollection<int, static>  $models
     * @return EloquentCollection<int, static>
     */
    public function makeSearchableUsing(EloquentCollection $models): EloquentCollection
    {
        return $models->loadMissing([
            'language',
            'examples' => fn ($q) => $q->orderBy('sort_order')->limit(12),
            'concept.domains.translations',
            'concept.outgoingRelations.relatedConcept.translations',
            'concept.incomingRelations.concept.translations',
        ]);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->whereHas('concept', fn (Builder $q) => $q->where('status', 'published'))
            ->whereHas('language', fn (Builder $q) => $q->where('is_active', true));
    }

    /**
     * Highlighted term from Meilisearch hit metadata, if present.
     */
    public function scoutHighlightedTerm(?string $needle = null): HtmlString
    {
        $formatted = $this->scoutMetadata['_formatted']['term'] ?? null;
        if (is_string($formatted) && $formatted !== '') {
            return new HtmlString($formatted);
        }

        return SearchHighlighter::mark($this->term, (string) ($needle ?? ''));
    }

    /**
     * Highlighted short line for dropdown / list (definition or examples).
     */
    public function scoutHighlightedSnippet(?string $needle = null): HtmlString
    {
        $formattedDef = $this->scoutMetadata['_formatted']['short_definition'] ?? null;
        if (is_string($formattedDef) && $formattedDef !== '') {
            return new HtmlString($formattedDef);
        }

        $formattedEx = $this->scoutMetadata['_formatted']['examples_snippet'] ?? null;
        if (is_string($formattedEx) && $formattedEx !== '') {
            return new HtmlString($formattedEx);
        }

        $plain = $this->short_definition ?? $this->full_definition ?? '';

        return SearchHighlighter::mark(Str::limit(strip_tags((string) $plain), 220), (string) ($needle ?? ''));
    }

    /**
     * Published concept translations for a UI locale (active language + published concept).
     *
     * @param  Builder<ConceptTranslation>  $query
     * @return Builder<ConceptTranslation>
     */
    public function scopeForPublishedLocale(Builder $query, string $localeCode): Builder
    {
        $languageId = Language::activeIdForCode($localeCode);
        if ($languageId === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->where('language_id', $languageId)
            ->whereHas('concept', fn (Builder $q) => $q->where('status', 'published'));
    }

    /**
     * Lexical match across term fields, domain copy (same language), and related concepts' terms in the same language.
     *
     * @param  Builder<ConceptTranslation>  $query
     * @return Builder<ConceptTranslation>
     */
    public function scopeSearchLexical(Builder $query, string $needle, int $languageId): Builder
    {
        $needle = trim($needle);
        if ($needle === '') {
            return $query->whereRaw('1 = 0');
        }

        $pattern = '%'.addcslashes($needle, '%_\\').'%';

        return $query->where(function (Builder $w) use ($pattern, $languageId): void {
            $w->where('term', 'like', $pattern)
                ->orWhere('slug', 'like', $pattern)
                ->orWhere('short_definition', 'like', $pattern)
                ->orWhere('full_definition', 'like', $pattern)
                ->orWhere('seo_title', 'like', $pattern)
                ->orWhere('seo_description', 'like', $pattern)
                ->orWhere('industry_notes', 'like', $pattern)
                ->orWhereHas('concept.domains.translations', function (Builder $dt) use ($pattern, $languageId): void {
                    $dt->where('language_id', $languageId)
                        ->where(function (Builder $d2) use ($pattern): void {
                            $d2->where('name', 'like', $pattern)
                                ->orWhere('description', 'like', $pattern);
                        });
                })
                ->orWhereHas('concept.outgoingRelations', function (Builder $rel) use ($pattern, $languageId): void {
                    $rel->whereHas('relatedConcept.translations', function (Builder $tr) use ($pattern, $languageId): void {
                        $tr->where('language_id', $languageId)
                            ->where(function (Builder $t2) use ($pattern): void {
                                $t2->where('term', 'like', $pattern)
                                    ->orWhere('short_definition', 'like', $pattern);
                            });
                    });
                })
                ->orWhereHas('concept.incomingRelations', function (Builder $rel) use ($pattern, $languageId): void {
                    $rel->whereHas('concept.translations', function (Builder $tr) use ($pattern, $languageId): void {
                        $tr->where('language_id', $languageId)
                            ->where(function (Builder $t2) use ($pattern): void {
                                $t2->where('term', 'like', $pattern)
                                    ->orWhere('short_definition', 'like', $pattern);
                            });
                    });
                });
        });
    }

    /**
     * Published concepts for a UI locale, filtered by the first letter of the translated term (A–Z).
     *
     * @param  Builder<ConceptTranslation>  $query
     * @return Builder<ConceptTranslation>
     */
    public function scopeForGlossaryLetter(Builder $query, string $languageCode, string $letter): Builder
    {
        $letter = mb_strtoupper(mb_substr($letter, 0, 1, 'UTF-8'), 'UTF-8');

        $languageId = Language::activeIdForCode($languageCode);

        if ($languageId === null) {
            return $query->whereRaw('1 = 0');
        }

        $expr = GlossaryLetterSql::firstUpperCharExpression($query->getConnection(), 'term');

        return $query
            ->where('language_id', $languageId)
            ->whereHas('concept', fn (Builder $c) => $c->where('status', 'published'))
            ->whereRaw("{$expr} = ?", [$letter]);
    }
}
