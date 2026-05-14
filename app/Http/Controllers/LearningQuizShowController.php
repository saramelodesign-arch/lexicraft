<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

final class LearningQuizShowController extends Controller
{
    public function __invoke(string $locale, string $slug): View
    {
        $quiz = Quiz::query()
            ->forLocale($locale)
            ->published()
            ->where('slug', $slug)
            ->with(['questions' => fn ($q) => $q->orderBy('sort_order')])
            ->firstOrFail();

        $pageTitle = $quiz->title;
        $metaDescription = $quiz->description
            ? strip_tags((string) $quiz->description)
            : __('Structured quiz: definitions, semantics, and matching for this locale.');

        $canonical = route('learning.quiz.show', ['locale' => $locale, 'slug' => $quiz->slug], absolute: true);

        return view('pages.learning.quiz-show', [
            'locale' => $locale,
            'quiz' => $quiz,
            'pageTitle' => $pageTitle,
            'metaDescription' => Str::limit($metaDescription, 165, '…'),
            'canonical' => $canonical,
        ]);
    }
}
