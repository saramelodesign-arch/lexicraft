<?php

namespace App\Models;

use Database\Factories\ConceptTranslationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConceptTranslation extends Model
{
    /** @use HasFactory<ConceptTranslationFactory> */
    use HasFactory;

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
}
