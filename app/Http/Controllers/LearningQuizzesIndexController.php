<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Contracts\View\View;

final class LearningQuizzesIndexController extends Controller
{
    public function __invoke(string $locale): View
    {
        $quizzes = Quiz::query()
            ->forLocale($locale)
            ->published()
            ->with('domain')
            ->orderBy('title')
            ->get();

        $pageTitle = __('Quizzes');
        $metaDescription = __('LexiCraft Glossary terminology assessments aligned with concepts and domains.');
        $canonical = route('learning.quizzes', ['locale' => $locale], absolute: true);

        return view('pages.learning.quizzes-index', [
            'locale' => $locale,
            'quizzes' => $quizzes,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
        ]);
    }
}
