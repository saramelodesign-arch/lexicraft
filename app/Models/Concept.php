<?php

namespace App\Models;

use Database\Factories\ConceptFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Concept extends Model
{
    /** @use HasFactory<ConceptFactory> */
    use HasFactory;

    protected $guarded = [];

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
}
