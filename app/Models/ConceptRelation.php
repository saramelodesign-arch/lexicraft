<?php

namespace App\Models;

use App\Support\SemanticGraph;
use Database\Factories\ConceptRelationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class ConceptRelation extends Model
{
    /** @use HasFactory<ConceptRelationFactory> */
    use HasFactory;

    protected $fillable = [
        'concept_id',
        'related_concept_id',
        'relation_type',
    ];

    protected static function booted(): void
    {
        static::saving(function (ConceptRelation $relation): void {
            if ($relation->concept_id === $relation->related_concept_id) {
                throw ValidationException::withMessages([
                    'related_concept_id' => __('admin.msg_cannot_relate_self'),
                ]);
            }

            SemanticGraph::assertAllowedStoredType($relation->relation_type);
        });
    }

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class, 'concept_id');
    }

    public function relatedConcept(): BelongsTo
    {
        return $this->belongsTo(Concept::class, 'related_concept_id');
    }
}
