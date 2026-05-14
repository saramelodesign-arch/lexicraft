<?php

namespace App\Models;

use App\Support\Locales;
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

    public function url(bool $absolute = false): string
    {
        $code = $this->relationLoaded('language')
            ? ($this->language?->code)
            : Language::query()->whereKey($this->language_id)->value('code');

        $locale = is_string($code) && Locales::isSupported($code) ? $code : Locales::fallback();

        return route('domains.show', [
            'locale' => $locale,
            'slug' => $this->slug,
        ], absolute: $absolute);
    }
}
