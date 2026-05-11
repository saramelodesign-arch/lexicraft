<?php

namespace App\Models;

use Database\Factories\DomainTranslationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DomainTranslation extends Model
{
    /** @use HasFactory<DomainTranslationFactory> */
    use HasFactory;

    protected $guarded = [];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
