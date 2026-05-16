<?php

namespace App\Models;

use Database\Factories\ExampleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Example extends Model
{
    /** @use HasFactory<ExampleFactory> */
    use HasFactory;

    protected $fillable = [
        'concept_translation_id',
        'example',
        'context',
        'sort_order',
    ];

    public function conceptTranslation(): BelongsTo
    {
        return $this->belongsTo(ConceptTranslation::class);
    }
}
