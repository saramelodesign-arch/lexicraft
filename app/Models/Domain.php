<?php

namespace App\Models;

use Database\Factories\DomainFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    /** @use HasFactory<DomainFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Domain::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Domain::class, 'parent_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(DomainTranslation::class);
    }

    public function concepts(): BelongsToMany
    {
        return $this->belongsToMany(Concept::class, 'concept_domain');
    }
}
