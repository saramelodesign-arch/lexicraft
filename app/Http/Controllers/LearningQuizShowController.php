<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Support\Locales;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

final class LearningQuizShowController extends Controller
{
    public function __invoke(string $locale, string $slug): View
    {
        $quiz = Quiz::resolvePublicBySlugOrFail($locale, $slug);

        $pageTitle = $quiz->title;
        $metaDescription = $quiz->description
            ? strip_tags((string) $quiz->description)
            : __('learning.structured_quiz_fallback');

        $canonical = route('learning.quiz.show', ['locale' => $locale, 'slug' => $quiz->slug], absolute: true);
        $alternates = [];
        foreach (Locales::codes() as $code) {
            $peer = Quiz::query()
                ->publicForLocale($code)
                ->where('slug', $quiz->slug)
                ->first();
            if ($peer !== null) {
                $alternates[$code] = route('learning.quiz.show', ['locale' => $code, 'slug' => $peer->slug], absolute: true);
            }
        }
        $xDefaultUrl = $alternates[Locales::fallback()] ?? (count($alternates) > 0 ? reset($alternates) : $canonical);

        return view('pages.learning.quiz-show', [
            'locale' => $locale,
            'quiz' => $quiz,
            'pageTitle' => $pageTitle,
            'metaDescription' => Str::limit($metaDescription, 165, '…'),
            'canonical' => $canonical,
            'alternates' => $alternates,
            'xDefaultUrl' => $xDefaultUrl,
        ]);
    }
}
