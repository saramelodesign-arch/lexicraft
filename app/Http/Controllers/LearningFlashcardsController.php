<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Language;
use App\Support\Locales;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class LearningFlashcardsController extends Controller
{
    public function __invoke(Request $request, string $locale): View
    {
        $domainSlug = $request->string('domain')->toString();
        $domainId = null;
        $domainLabel = null;
        if ($domainSlug !== '') {
            $languageId = Language::activeIdForCode($locale);
            if ($languageId !== null) {
                $domain = Domain::query()
                    ->where('is_active', true)
                    ->whereHas('translations', fn ($q) => $q->where('slug', $domainSlug)->where('language_id', $languageId))
                    ->first();
                if ($domain !== null) {
                    $domainId = $domain->id;
                    $domainLabel = $domain->translationForLocale($locale)?->name ?? $domain->slug;
                }
            }
        }

        $pageTitle = $domainLabel
            ? __('learning.flashcards_with_domain', ['domain' => $domainLabel])
            : __('ui.flashcards');

        $metaDescription = __('learning.flashcards_meta_description');

        $canonical = $domainSlug !== ''
            ? route('learning.flashcards', ['locale' => $locale, 'domain' => $domainSlug], absolute: true)
            : route('learning.flashcards', ['locale' => $locale], absolute: true);
        $alternates = [];
        if ($domainSlug === '') {
            foreach (Locales::codes() as $code) {
                $alternates[$code] = route('learning.flashcards', ['locale' => $code], absolute: true);
            }
        }
        $xDefaultUrl = $alternates[Locales::fallback()] ?? (count($alternates) > 0 ? reset($alternates) : $canonical);

        return view('pages.learning.flashcards', [
            'locale' => $locale,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'alternates' => $alternates,
            'xDefaultUrl' => $xDefaultUrl,
            'domainSlug' => $domainSlug !== '' ? $domainSlug : null,
            'domainId' => $domainId,
            'robotsMeta' => 'noindex,follow',
        ]);
    }
}
