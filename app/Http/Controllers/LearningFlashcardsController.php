<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Language;
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
            ? __('Flashcards — :domain', ['domain' => $domainLabel])
            : __('Flashcards');

        $metaDescription = __(
            'Flip cards grounded in LexiCraft Glossary: published definitions, domains, examples, and semantic hints for this locale.',
        );

        $canonical = $domainSlug !== ''
            ? route('learning.flashcards', ['locale' => $locale, 'domain' => $domainSlug], absolute: true)
            : route('learning.flashcards', ['locale' => $locale], absolute: true);

        return view('pages.learning.flashcards', [
            'locale' => $locale,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'domainSlug' => $domainSlug !== '' ? $domainSlug : null,
            'domainId' => $domainId,
            'robotsMeta' => 'noindex,follow',
        ]);
    }
}
