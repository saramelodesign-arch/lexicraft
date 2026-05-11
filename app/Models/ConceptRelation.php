<?php

namespace App\Models;

use Database\Factories\ConceptRelationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConceptRelation extends Model
{
    /** @use HasFactory<ConceptRelationFactory> */
    use HasFactory;

    protected $guarded = [];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class, 'concept_id');
    }

    public function relatedConcept(): BelongsTo
    {
        return $this->belongsTo(Concept::class, 'related_concept_id');
    }
}
