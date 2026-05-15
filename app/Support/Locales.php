<?php

namespace App\Support;

use App\Models\ConceptTranslation;
use App\Models\DomainTranslation;
use App\Models\Language;
use App\Support\Editorial\WorkflowStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Supported UI / content locale codes and metadata.
 *
 * Source of truth: config/locales.php. Use this class in PHP (routes,
 * middleware, services) so validation stays aligned with the config.
 */
final class Locales
{
    /**
     * @return array<string, array{name: string, native: string}>
     */
    public static function supported(): array
    {
        return config('locales.supported', []);
    }

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_keys(self::supported());
    }

    public static function isSupported(string $locale): bool
    {
        return array_key_exists($locale, self::supported());
    }

    public static function isSupportedOrNull(mixed $locale): bool
    {
        return is_string($locale) && self::isSupported($locale);
    }

    public static function fallback(): string
    {
        $fallback = config('locales.fallback');

        return is_string($fallback) && self::isSupported($fallback)
            ? $fallback
            : 'en';
    }

    /**
     * Route constraint pattern for `{locale}` (e.g. `en|pt|fr`).
     */
    public static function localeRoutePattern(): string
    {
        return implode('|', self::codes());
    }

    /**
     * Validation rule for locale fields.
     */
    public static function localeRule()
    {
        return Rule::in(self::codes());
    }

    /**
     * Preferred locale priority:
     * 1) authenticated user preference
     * 2) session / cookie
     * 3) legacy query (?locale=)
     * 4) fallback
     */
    public static function preferredFromRequest(Request $request): string
    {
        $userLocale = Auth::user()?->preferred_locale;
        if (self::isSupportedOrNull($userLocale)) {
            return $userLocale;
        }

        if ($request->hasSession()) {
            $sessionLocale = $request->session()->get('locale');
            if (self::isSupportedOrNull($sessionLocale)) {
                return $sessionLocale;
            }
        }

        $cookieLocale = $request->cookie('locale');
        if (self::isSupportedOrNull($cookieLocale)) {
            return $cookieLocale;
        }

        $query = $request->query('locale');
        if (self::isSupportedOrNull($query)) {
            return $query;
        }

        return self::fallback();
    }

    /**
     * Active locale for URL generation: route parameter when present, else preference.
     */
    public static function current(): string
    {
        $routeLocale = request()->route('locale');
        if (is_string($routeLocale) && self::isSupported($routeLocale)) {
            return $routeLocale;
        }

        return self::preferredFromRequest(request());
    }

    public static function homeUrl(): string
    {
        return route('home', ['locale' => self::current()], absolute: false);
    }

    /**
     * Same path as the current request with a different leading locale segment.
     * Non-localized paths fall back to the localized home URL for the target locale.
     */
    public static function localizedUrl(string $targetLocale): string
    {
        if (! self::isSupported($targetLocale)) {
            $targetLocale = self::fallback();
        }

        $path = trim(request()->path(), '/');
        if ($path === '') {
            return self::withRequestQueryString(route('home', ['locale' => $targetLocale], absolute: false));
        }

        $segments = explode('/', $path);
        if (isset($segments[0]) && self::isSupported($segments[0])) {
            $domainUrl = self::localizedDomainUrl($segments, $targetLocale);
            if ($domainUrl !== null) {
                return self::withRequestQueryString($domainUrl);
            }

            $conceptUrl = self::localizedGlossaryConceptUrl($segments, $targetLocale);
            if ($conceptUrl !== null) {
                return self::withRequestQueryString($conceptUrl);
            }

            $segments[0] = $targetLocale;

            return self::withRequestQueryString(url('/'.implode('/', $segments)));
        }

        return self::withRequestQueryString(route('home', ['locale' => $targetLocale], absolute: false));
    }

    /**
     * Preserve the current request query string (e.g. ?q=, ?page=) when swapping the locale prefix.
     */
    public static function withRequestQueryString(string $url): string
    {
        $qs = request()->getQueryString();
        if ($qs === null || $qs === '') {
            return $url;
        }

        $sep = str_contains($url, '?') ? '&' : '?';

        return $url.$sep.$qs;
    }

    /**
     * When the path is /{locale}/domains/{slug}, map to the same domain's slug in the target locale.
     */
    public static function localizedDomainUrl(array $segments, string $targetLocale): ?string
    {
        if (count($segments) !== 3 || $segments[1] !== 'domains') {
            return null;
        }

        $sourceLocale = $segments[0];
        $slug = mb_strtolower($segments[2], 'UTF-8');

        if (! self::isSupported($sourceLocale) || ! self::isSupported($targetLocale)) {
            return null;
        }

        $sourceLanguageId = Language::activeIdForCode($sourceLocale);

        if ($sourceLanguageId === null) {
            return null;
        }

        $source = DomainTranslation::query()
            ->where('slug', $slug)
            ->where('language_id', $sourceLanguageId)
            ->whereHas('domain', fn ($q) => $q->where('is_active', true))
            ->first();

        if ($source === null) {
            return null;
        }

        $target = $source->domain?->translationForLocale($targetLocale);
        if ($target === null || $target->slug === '') {
            return route('domains.index', ['locale' => $targetLocale], absolute: false);
        }

        return route('domains.show', ['locale' => $targetLocale, 'slug' => $target->slug], absolute: false);
    }

    /**
     * When the path is /{locale}/glossary/{slug} and {slug} is not a single-letter index,
     * rewrite to the same concept's slug in the target locale.
     */
    public static function localizedGlossaryConceptUrl(array $segments, string $targetLocale): ?string
    {
        if (count($segments) !== 3 || $segments[1] !== 'glossary') {
            return null;
        }

        $sourceLocale = $segments[0];
        $piece = $segments[2];

        if (preg_match('/^[A-Za-z]$/', $piece) === 1) {
            return null;
        }

        if (! self::isSupported($sourceLocale) || ! self::isSupported($targetLocale)) {
            return null;
        }

        $slug = mb_strtolower($piece, 'UTF-8');

        $sourceLanguageId = Language::activeIdForCode($sourceLocale);

        if ($sourceLanguageId === null) {
            return null;
        }

        $source = ConceptTranslation::query()
            ->where('slug', $slug)
            ->where('language_id', $sourceLanguageId)
            ->where('status', WorkflowStatus::PUBLISHED)
            ->whereHas('concept', fn ($q) => $q->where('status', 'published'))
            ->first();

        if ($source === null) {
            return null;
        }

        $target = $source->concept?->translationForLocale($targetLocale);
        if ($target === null || $target->status !== WorkflowStatus::PUBLISHED) {
            return route('home', ['locale' => $targetLocale], absolute: false);
        }

        return route('glossary.concept', ['locale' => $targetLocale, 'slug' => $target->slug], absolute: false);
    }
}
