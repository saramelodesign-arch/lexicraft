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

    /** @var array<string, int|null> */
    private static array $activeIdByCode = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (): void {
            self::$activeIdByCode = [];
        });

        static::deleted(function (): void {
            self::$activeIdByCode = [];
        });
    }

    /**
     * Cached id for an active language row by ISO code (e.g. en). Single source for locale→id across HTTP stack.
     */
    public static function activeIdForCode(string $code): ?int
    {
        if (! array_key_exists($code, self::$activeIdByCode)) {
            self::$activeIdByCode[$code] = static::query()
                ->where('code', $code)
                ->where('is_active', true)
                ->value('id');
        }

        return self::$activeIdByCode[$code];
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
