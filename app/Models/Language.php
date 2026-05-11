<?php

namespace App\Models;

use Database\Factories\LanguageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    /** @use HasFactory<LanguageFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function domainTranslations(): HasMany
    {
        return $this->hasMany(DomainTranslation::class);
    }

    public function conceptTranslations(): HasMany
    {
        return $this->hasMany(ConceptTranslation::class);
    }
}
