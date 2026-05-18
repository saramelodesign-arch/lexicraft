<?php

namespace App\Models;

use App\Support\ConceptSearchDocument;
use App\Support\Editorial\TerminologyStatus;
use App\Support\Editorial\WorkflowStatus;
use App\Support\GlossaryLetterSql;
use App\Support\Locales;
use App\Support\Search\QueuedSearchIndexer;
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

    protected $fillable = [
        'concept_id',
        'language_id',
        'status',
        'terminology_status',
        'validated_at',
        'validated_by',
        'term',
        'slug',
        'short_definition',
        'full_definition',
        'seo_title',
        'seo_description',
        'seo_canonical_url',
        'og_title',
        'og_description',
        'meta_keywords',
        'industry_notes',
        'editorial_notes',
        'source_reference_text',
    ];

    protected function casts(): array
    {
        return [
            'validated_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ConceptTranslation $translation): void {
            if (WorkflowStatus::isValid((string) $translation->status)) {
                if (! TerminologyStatus::isValid((string) $translation->terminology_status)) {
                    $translation->terminology_status = TerminologyStatus::DRAFT;
                }
                return;
            }

            $conceptStatus = Concept::query()->whereKey($translation->concept_id)->value('status');
            $translation->status = is_string($conceptStatus) && WorkflowStatus::isPublic($conceptStatus)
                ? WorkflowStatus::PUBLISHED
                : WorkflowStatus::DRAFT;
            if (! TerminologyStatus::isValid((string) $translation->terminology_status)) {
                $translation->terminology_status = TerminologyStatus::DRAFT;
            }
        });

        static::saved(function (ConceptTranslation $translation): void {
            QueuedSearchIndexer::queueTranslation((int) $translation->id);
        });

        static::deleted(function (ConceptTranslation $translation): void {
            QueuedSearchIndexer::queueTranslation((int) $translation->id);
        });
    }

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
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

        if ($this->concept?->status !== WorkflowStatus::PUBLISHED) {
            return false;
        }

        if ($this->status !== WorkflowStatus::PUBLISHED) {
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
        return $query
            ->where('status', WorkflowStatus::PUBLISHED)
            ->whereHas('concept', fn (Builder $q) => $q->where('status', WorkflowStatus::PUBLISHED))
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
            ->where('status', WorkflowStatus::PUBLISHED)
            ->whereHas('concept', fn (Builder $q) => $q->where('status', WorkflowStatus::PUBLISHED));
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

        $tokens = self::searchTokens($needle);
        if ($tokens === []) {
            return $query->whereRaw('1 = 0');
        }

        $prefixPattern = self::prefixPattern($tokens[0]);
        $containsPattern = self::containsPattern($needle);
        $relationPattern = self::containsPattern($tokens[0]);
        $isPostgres = $query->getConnection()->getDriverName() === 'pgsql';
        $operator = $isPostgres ? 'ILIKE' : 'LIKE';

        return $query->where(function (Builder $w) use ($containsPattern, $isPostgres, $languageId, $needle, $operator, $prefixPattern, $relationPattern, $tokens): void {
            // Fast path first: prefix matches on indexed short fields.
            $w->where('term', $operator, $prefixPattern)
                ->orWhere('slug', $operator, $prefixPattern);

            if ($isPostgres) {
                $w->orWhereRaw(
                    "to_tsvector('simple', concat_ws(' ', coalesce(term, ''), coalesce(short_definition, ''), coalesce(full_definition, ''), coalesce(seo_title, ''), coalesce(seo_description, ''), coalesce(industry_notes, ''), coalesce(editorial_notes, ''))) @@ plainto_tsquery('simple', ?)",
                    [$needle],
                );
            } else {
                // Keep wildcard fallback narrower to avoid full-table wildcard chains.
                $w->orWhere('short_definition', 'LIKE', $containsPattern)
                    ->orWhere('seo_title', 'LIKE', $containsPattern)
                    ->orWhere('industry_notes', 'LIKE', $containsPattern)
                    ->orWhere('editorial_notes', 'LIKE', $containsPattern);
            }

            if (mb_strlen($needle) < 4) {
                return;
            }

            $w->orWhereHas('concept.domains.translations', function (Builder $dt) use ($languageId, $operator, $relationPattern): void {
                $dt->where('language_id', $languageId)
                    ->where(function (Builder $d2) use ($operator, $relationPattern): void {
                        $d2->where('name', $operator, $relationPattern)
                            ->orWhere('description', $operator, $relationPattern);
                    });
            });

            if (count($tokens) > 4) {
                return;
            }

            $w->orWhereHas('concept.outgoingRelations', function (Builder $rel) use ($languageId, $operator, $relationPattern): void {
                $rel->whereHas('relatedConcept.translations', function (Builder $tr) use ($languageId, $operator, $relationPattern): void {
                    $tr->where('language_id', $languageId)
                        ->where('status', WorkflowStatus::PUBLISHED)
                        ->whereHas('concept', fn (Builder $cq) => $cq->where('status', WorkflowStatus::PUBLISHED))
                        ->where(function (Builder $t2) use ($operator, $relationPattern): void {
                            $t2->where('term', $operator, $relationPattern)
                                ->orWhere('short_definition', $operator, $relationPattern);
                        });
                });
            })->orWhereHas('concept.incomingRelations', function (Builder $rel) use ($languageId, $operator, $relationPattern): void {
                $rel->whereHas('concept.translations', function (Builder $tr) use ($languageId, $operator, $relationPattern): void {
                    $tr->where('language_id', $languageId)
                        ->where('status', WorkflowStatus::PUBLISHED)
                        ->whereHas('concept', fn (Builder $cq) => $cq->where('status', WorkflowStatus::PUBLISHED))
                        ->where(function (Builder $t2) use ($operator, $relationPattern): void {
                            $t2->where('term', $operator, $relationPattern)
                                ->orWhere('short_definition', $operator, $relationPattern);
                        });
                });
            });
        });
    }

    private static function containsPattern(string $value): string
    {
        return '%'.addcslashes($value, '%_\\').'%';
    }

    private static function prefixPattern(string $value): string
    {
        return addcslashes($value, '%_\\').'%';
    }

    /**
     * @return list<string>
     */
    private static function searchTokens(string $needle): array
    {
        $parts = preg_split('/\s+/u', mb_strtolower(trim($needle)));
        if (! is_array($parts)) {
            return [];
        }

        $tokens = array_values(array_filter(array_map(
            static fn (string $part): string => preg_replace('/[^\p{L}\p{N}_-]+/u', '', $part) ?? '',
            $parts,
        ), static fn (string $token): bool => mb_strlen($token) >= 2));

        return array_slice($tokens, 0, 5);
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
            ->where('status', WorkflowStatus::PUBLISHED)
            ->whereHas('concept', fn (Builder $c) => $c->where('status', WorkflowStatus::PUBLISHED))
            ->whereRaw("{$expr} = ?", [$letter]);
    }
}
