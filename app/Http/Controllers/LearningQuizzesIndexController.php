<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Support\Locales;
use Illuminate\Contracts\View\View;

final class LearningQuizzesIndexController extends Controller
{
    public function __invoke(string $locale): View
    {
        $quizzes = Quiz::query()
            ->publicForLocale($locale)
            ->with('domain')
            ->orderBy('title')
            ->get();

        $pageTitle = __('ui.quizzes');
        $metaDescription = __('learning.quizzes_meta_description');
        $canonical = route('learning.quizzes', ['locale' => $locale], absolute: true);
        $alternates = [];
        foreach (Locales::codes() as $code) {
            $alternates[$code] = route('learning.quizzes', ['locale' => $code], absolute: true);
        }
        $xDefaultUrl = $alternates[Locales::fallback()] ?? (count($alternates) > 0 ? reset($alternates) : $canonical);

        return view('pages.learning.quizzes-index', [
            'locale' => $locale,
            'quizzes' => $quizzes,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'alternates' => $alternates,
            'xDefaultUrl' => $xDefaultUrl,
        ]);
    }
}
