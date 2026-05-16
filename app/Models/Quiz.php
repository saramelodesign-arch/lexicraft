<?php

namespace App\Models;

use Database\Factories\QuizFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    /** @use HasFactory<QuizFactory> */
    use HasFactory;

    protected $fillable = [
        'locale',
        'slug',
        'title',
        'description',
        'domain_id',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublicForLocale($query, string $locale)
    {
        return $query->forLocale($locale)->published();
    }

    public static function resolvePublicBySlugOrFail(string $locale, string $slug): self
    {
        return static::query()
            ->publicForLocale($locale)
            ->where('slug', $slug)
            ->with(['questions' => fn ($q) => $q->orderBy('sort_order')])
            ->firstOrFail();
    }

    public static function resolvePublicByIdOrFail(string $locale, int $id): self
    {
        return static::query()
            ->publicForLocale($locale)
            ->whereKey($id)
            ->with(['questions' => fn ($q) => $q->orderBy('sort_order')])
            ->firstOrFail();
    }
}
