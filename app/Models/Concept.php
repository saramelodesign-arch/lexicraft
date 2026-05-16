<?php

namespace App\Models;

use App\Support\Editorial\WorkflowStatus;
use App\Support\Search\QueuedSearchIndexer;
use Database\Factories\ConceptFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Domain aggregate for media and semantic relations. Glossary search indexes
 * {@see ConceptTranslation} rows (one Meilisearch document per locale), not this model.
 */
class Concept extends Model implements HasMedia
{
    /** @use HasFactory<ConceptFactory> */
    use HasFactory;

    use InteractsWithMedia;

    public const string COLLECTION_FEATURED = 'featured';

    public const string COLLECTION_GALLERY = 'gallery';

    public const string COLLECTION_VIDEOS = 'videos';

    public const string COLLECTION_DOCUMENTS = 'documents';

    protected $fillable = [
        'uuid',
        'status',
        'difficulty_level',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Concept $concept): void {
            $concept->uuid ??= (string) Str::uuid();
            if (! WorkflowStatus::isValid((string) $concept->status)) {
                $concept->status = WorkflowStatus::DRAFT;
            }
        });

        static::saved(function (Concept $concept): void {
            if ($concept->wasChanged('status')) {
                QueuedSearchIndexer::queueConcept((int) $concept->id);
            }
        });

        static::deleting(function (Concept $concept): void {
            $translationIds = $concept->translations()
                ->select('id')
                ->pluck('id')
                ->map(static fn (mixed $id): int => (int) $id)
                ->all();

            QueuedSearchIndexer::queueConceptRemoval((int) $concept->id, $translationIds);
        });
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ConceptTranslation::class);
    }

    public function domains(): BelongsToMany
    {
        return $this->belongsToMany(Domain::class, 'concept_domain');
    }

    public function outgoingRelations(): HasMany
    {
        return $this->hasMany(ConceptRelation::class, 'concept_id');
    }

    public function incomingRelations(): HasMany
    {
        return $this->hasMany(ConceptRelation::class, 'related_concept_id');
    }

    public function translationForLocale(string $localeCode): ?ConceptTranslation
    {
        $languageId = Language::activeIdForCode($localeCode);
        if ($languageId === null) {
            return null;
        }

        if ($this->relationLoaded('translations')) {
            $hit = $this->translations->first(
                fn (ConceptTranslation $t): bool => $t->language_id === $languageId,
            );
            if ($hit !== null) {
                return $hit;
            }
        }

        return $this->translations()->where('language_id', $languageId)->first();
    }

    public function registerMediaCollections(): void
    {
        $disk = config('media-library.disk_name', 'public');

        $this->addMediaCollection(self::COLLECTION_FEATURED)
            ->useDisk($disk)
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

        $this->addMediaCollection(self::COLLECTION_GALLERY)
            ->useDisk($disk)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

        $this->addMediaCollection(self::COLLECTION_VIDEOS)
            ->useDisk($disk)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'video/mp4']);

        $this->addMediaCollection(self::COLLECTION_DOCUMENTS)
            ->useDisk($disk)
            ->acceptsMimeTypes(['application/pdf']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('card')
            ->width(960)
            ->quality(88)
            ->nonQueued()
            ->performOnCollections(self::COLLECTION_FEATURED, self::COLLECTION_GALLERY);

        $this->addMediaConversion('thumb')
            ->width(420)
            ->quality(85)
            ->nonQueued()
            ->performOnCollections(self::COLLECTION_FEATURED, self::COLLECTION_GALLERY);
    }
}
